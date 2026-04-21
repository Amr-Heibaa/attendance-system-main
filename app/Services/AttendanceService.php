<?php

namespace App\Services;

use App\Models\AttendanceRecord;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\WorkSchedule;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\NotificationService;

class AttendanceService
{
    public function checkIn(Employee $employee): AttendanceRecord
    {
        $today = now()->toDateString();

        $existing = AttendanceRecord::where('employee_id', $employee->id)
            ->where('date', $today)
            ->first();

        if ($existing) {
            throw new \Exception('تم تسجيل حضورك بالفعل اليوم');
        }

        $checkInTime = now();
        $schedule = $this->getApplicableSchedule($employee, $today);
        $lateMinutes = 0;
        $status = 'present';

        if ($schedule) {
            $startTime = Carbon::parse($today . ' ' . $schedule['start_time']);
            $allowedTime = $startTime->copy()->addMinutes($schedule['grace_minutes']);

            if ($checkInTime->gt($allowedTime)) {
                $lateMinutes = $startTime->diffInMinutes($checkInTime);
                $status = 'late';
            }
        }

        $record = AttendanceRecord::create([
            'employee_id'  => $employee->id,
            'date'         => $today,
            'check_in'     => $checkInTime,
            'status'       => $status,
            'late_minutes' => $lateMinutes,
        ]);

        if ($record->status === 'late') {
            app(NotificationService::class)->create(
                $employee->user_id,
                'late',
                'تأخير في الحضور',
                'تم تسجيل تأخير اليوم بعدد ' . $record->late_minutes . ' دقيقة'
            );
        }

        return $record;
    }

    public function checkOut(Employee $employee): AttendanceRecord
    {
        $today = now()->toDateString();

        $record = AttendanceRecord::where('employee_id', $employee->id)
            ->where('date', $today)
            ->whereNotNull('check_in')
            ->whereNull('check_out')
            ->first();

        if (!$record) {
            throw new \Exception('لم يتم تسجيل الحضور أو تم تسجيل الانصراف مسبقاً');
        }

        $checkOutTime = now();
        $workMinutes = Carbon::parse($record->check_in)->diffInMinutes($checkOutTime);

        $earlyLeaveMinutes = 0;
        $schedule = $this->getApplicableSchedule($employee, $today);

        if ($schedule) {
            $endTime = Carbon::parse($today . ' ' . $schedule['end_time']);

            if ($checkOutTime->lt($endTime)) {
                $earlyLeaveMinutes = $checkOutTime->diffInMinutes($endTime);
            }
        }

        $record->update([
            'check_out'           => $checkOutTime,
            'work_minutes'        => $workMinutes,
            'early_leave_minutes' => $earlyLeaveMinutes,
        ]);

        return $record;
    }

    public function getTodayStats(): array
    {
        $today = now()->toDateString();
        $totalEmployees = Employee::where('status', 'active')->count();

        $presentCount = AttendanceRecord::whereDate('date', $today)
            ->whereIn('status', ['present', 'late'])
            ->count();

        $lateCount = AttendanceRecord::whereDate('date', $today)
            ->where('status', 'late')
            ->count();

        $leaveCount = AttendanceRecord::whereDate('date', $today)
            ->where('status', 'leave')
            ->count();

        $isHoliday = Holiday::whereDate('date', $today)->exists();
        $absentCount = $isHoliday ? 0 : max(0, $totalEmployees - $presentCount - $leaveCount);

        $avgWorkMinutes = AttendanceRecord::whereDate('date', $today)
            ->whereNotNull('check_out')
            ->avg('work_minutes') ?? 0;

        $effectiveTotal = max(1, $totalEmployees - $leaveCount);
        $complianceRate = round(($presentCount / $effectiveTotal) * 100, 1);

        return [
            'total'           => $totalEmployees,
            'present'         => $presentCount,
            'absent'          => $absentCount,
            'late'            => $lateCount,
            'on_leave'        => $leaveCount,
            'avg_work_hours'  => round($avgWorkMinutes / 60, 1),
            'compliance_rate' => $complianceRate,
            'is_holiday'      => $isHoliday,
        ];
    }

    public function getAttendanceTrend(int $days = 30): array
    {
        $start = now()->subDays($days)->startOfDay();
        $end = now()->endOfDay();

        $raw = AttendanceRecord::select(
            DB::raw('DATE(date) as day'),
            DB::raw('COUNT(CASE WHEN status IN ("present","late") THEN 1 END) as present_count'),
            DB::raw('COUNT(CASE WHEN status = "late" THEN 1 END) as late_count')
        )
            ->whereBetween('date', [$start, $end])
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->keyBy('day');

        $data = [];

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $key = $date->toDateString();
            $row = $raw->get($key);

            $data[] = [
                'day' => $key,
                'present_count' => $row?->present_count ?? 0,
                'late_count' => $row?->late_count ?? 0,
            ];
        }

        return $data;
    }

    public function getTodayAlerts(): array
    {
        $today = now()->toDateString();

        return AttendanceRecord::with('employee.user')
            ->whereDate('date', $today)
            ->whereIn('status', ['late'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($row) {
                return [
                    'name' => $row->employee->user->name,
                    'late_minutes' => $row->late_minutes,
                ];
            })
            ->toArray();
    }

    public function getDepartmentStats(): array
    {
        $today = now()->toDateString();

        return DB::table('departments')
            ->leftJoin('employees', 'employees.department_id', '=', 'departments.id')
            ->leftJoin('attendance_records', function ($join) use ($today) {
                $join->on('attendance_records.employee_id', '=', 'employees.id')
                    ->whereDate('attendance_records.date', $today);
            })
            ->select(
                'departments.id',
                'departments.name_ar',
                'departments.name',
                DB::raw('COUNT(DISTINCT employees.id) as total'),
                DB::raw('COUNT(CASE WHEN attendance_records.status IN ("present","late") THEN 1 END) as present')
            )
            ->groupBy('departments.id', 'departments.name_ar', 'departments.name')
            ->get()
            ->map(function ($row) {
                return [
                    'name' => $row->name_ar ?? $row->name ?? 'بدون اسم',
                    'total' => $row->total,
                    'present' => $row->present,
                    'rate' => $row->total > 0 ? round(($row->present / $row->total) * 100) : 0,
                ];
            })
            ->toArray();
    }

    private function getApplicableSchedule(Employee $employee, string $date): ?array
    {
        $workSchedule = WorkSchedule::getActiveForDate($date);

        if ($workSchedule) {
            return [
                'start_time' => $workSchedule->start_time,
                'end_time' => $workSchedule->end_time,
                'grace_minutes' => $workSchedule->grace_minutes ?? 15,
                'source' => 'work_schedule',
                'name' => $workSchedule->name,
            ];
        }

        if ($employee->shift) {
            return [
                'start_time' => $employee->shift->start_time,
                'end_time' => $employee->shift->end_time,
                'grace_minutes' => $employee->shift->grace_minutes ?? 15,
                'source' => 'shift',
                'name' => $employee->shift->name,
            ];
        }

        return null;
    }

    public function isHoliday(string $date): bool
    {
        return Holiday::whereDate('date', $date)->exists();
    }

    public function hasApprovedLeave(Employee $employee, string $date): bool
    {
        return LeaveRequest::where('employee_id', $employee->id)
            ->where('status', 'approved')
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->exists();
    }
}