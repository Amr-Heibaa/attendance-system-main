<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use App\Models\Employee;
use App\Models\AttendanceRecord;
use App\Models\Holiday;       // تأكد عندك موديل Holidays
use App\Models\LeaveRequest;  // تأكد عندك موديل Leave Requests

class CloseDailyAttendance extends Command
{
    protected $signature = 'attendance:close-daily {date? : YYYY-MM-DD}';
    protected $description = 'Auto close daily attendance: absent/late/present/leave/holiday + handle missing checkout';

    public function handle(): int
    {
        $date = $this->argument('date')
            ? Carbon::parse($this->argument('date'))->startOfDay()
            : now()->startOfDay();

        $this->info("Closing attendance for: {$date->toDateString()}");

        $employees = Employee::with(['shift'])
            ->where('status', 'active')
            ->get();

        foreach ($employees as $employee) {

            // لو الموظف مالوش شيفت، اعتبره absent (أو تجاهله حسب رغبتك)
            if (!$employee->shift) {
                $this->upsertAbsent($employee->id, $date, 'no_shift');
                continue;
            }

            // 1) Weekend (Fri/Sat) -> holiday + note weekend
            if ($this->isWeekend($date)) {
                $this->upsertHoliday($employee->id, $date, 'weekend');
                continue;
            }

            // 2) Official holiday (من جدول holidays)
            if ($this->isOfficialHoliday($date)) {
                $this->upsertHoliday($employee->id, $date, 'official_holiday');
                continue;
            }

            // 3) Approved leave
            if ($this->hasApprovedLeave($employee->id, $date)) {
                $this->upsertLeave($employee->id, $date);
                continue;
            }

            // 4) Get or create record for day
            $record = AttendanceRecord::firstOrCreate(
                ['employee_id' => $employee->id, 'date' => $date->toDateString()],
                ['status' => 'absent'] // default لو لسه فاضي
            );

            // لو مفيش check_in => absent
            if (!$record->check_in) {
                $record->status = 'absent';
                $record->late_minutes = 0;
                $record->early_leave_minutes = 0;
                $record->work_minutes = 0;
                $record->notes = $this->mergeNotes($record->notes, 'auto:absent_no_checkin');
                $record->save();
                continue;
            }

            // 5) Determine late/present based on shift start + grace
            $shiftStart = Carbon::parse($date->toDateString().' '.$employee->shift->start_time);
            $grace = (int) ($employee->shift->grace_minutes ?? 15);
            $latestOnTime = $shiftStart->copy()->addMinutes($grace);

            $checkIn = Carbon::parse($record->check_in);

            $lateMinutes = 0;
            if ($checkIn->greaterThan($latestOnTime)) {
                $lateMinutes = $latestOnTime->diffInMinutes($checkIn);
                $record->status = 'late';
            } else {
                $record->status = 'present';
            }
            $record->late_minutes = $lateMinutes;

            // 6) Missing checkout policy: incomplete + work_minutes=0
            if (!$record->check_out) {
                $record->work_minutes = 0;
                $record->early_leave_minutes = 0;
                $record->notes = $this->mergeNotes($record->notes, 'auto:incomplete_missing_checkout');
                $record->save();
                continue;
            }

            // 7) Calculate work minutes (simple)
            $checkOut = Carbon::parse($record->check_out);
            if ($checkOut->lessThanOrEqualTo($checkIn)) {
                // حماية من بيانات غلط
                $record->work_minutes = 0;
                $record->notes = $this->mergeNotes($record->notes, 'auto:invalid_checkout_before_checkin');
                $record->save();
                continue;
            }

            $record->work_minutes = $checkIn->diffInMinutes($checkOut);

            // Optional: early leave vs shift end
            $shiftEnd = Carbon::parse($date->toDateString().' '.$employee->shift->end_time);
            // لو الشيفت بيعدي منتصف الليل (end_time أصغر من start_time) عدّل نهاية الشيفت لليوم اللي بعده
            if (Carbon::parse($employee->shift->end_time)->lessThan(Carbon::parse($employee->shift->start_time))) {
                $shiftEnd->addDay();
            }
            $earlyLeave = 0;
            if ($checkOut->lessThan($shiftEnd)) {
                $earlyLeave = $checkOut->diffInMinutes($shiftEnd);
            }
            $record->early_leave_minutes = $earlyLeave;

            $record->save();
        }

        $this->info('Done ✅');
        return self::SUCCESS;
    }

    private function isWeekend(Carbon $date): bool
    {
        // Friday/Saturday
        return in_array($date->dayOfWeekIso, [5, 6], true);
    }

    private function isOfficialHoliday(Carbon $date): bool
    {
        // حسب جدول holidays: غالبًا فيه date أو من/إلى… عدّل حسب موديلك
        // لو جدولك فيه عمود date:
        return class_exists(Holiday::class)
            ? Holiday::whereDate('date', $date->toDateString())->exists()
            : false;
    }

    private function hasApprovedLeave(int $employeeId, Carbon $date): bool
    {
        // عدّل أسماء الأعمدة حسب جدول leave_requests عندك
        // مثال شائع: start_date/end_date/status=approved
        return class_exists(LeaveRequest::class)
            ? LeaveRequest::where('employee_id', $employeeId)
                ->where('status', 'approved')
                ->whereDate('start_date', '<=', $date->toDateString())
                ->whereDate('end_date', '>=', $date->toDateString())
                ->exists()
            : false;
    }

    private function upsertHoliday(int $employeeId, Carbon $date, string $reason): void
    {
        $record = AttendanceRecord::updateOrCreate(
            ['employee_id' => $employeeId, 'date' => $date->toDateString()],
            ['status' => 'holiday', 'late_minutes' => 0, 'early_leave_minutes' => 0, 'work_minutes' => 0]
        );

        $record->notes = $this->mergeNotes($record->notes, "auto:holiday_{$reason}");
        $record->save();
    }

    private function upsertLeave(int $employeeId, Carbon $date): void
    {
        $record = AttendanceRecord::updateOrCreate(
            ['employee_id' => $employeeId, 'date' => $date->toDateString()],
            ['status' => 'leave', 'late_minutes' => 0, 'early_leave_minutes' => 0, 'work_minutes' => 0]
        );

        $record->notes = $this->mergeNotes($record->notes, "auto:leave");
        $record->save();
    }

    private function upsertAbsent(int $employeeId, Carbon $date, string $reason): void
    {
        $record = AttendanceRecord::updateOrCreate(
            ['employee_id' => $employeeId, 'date' => $date->toDateString()],
            ['status' => 'absent', 'late_minutes' => 0, 'early_leave_minutes' => 0, 'work_minutes' => 0]
        );

        $record->notes = $this->mergeNotes($record->notes, "auto:absent_{$reason}");
        $record->save();
    }

    private function mergeNotes(?string $existing, string $new): string
    {
        $existing = trim((string)$existing);
        if ($existing === '') return $new;
        if (str_contains($existing, $new)) return $existing;
        return $existing . ' | ' . $new;
    }
}