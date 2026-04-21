<?php

namespace App\Services;

use App\Models\AttendanceRecord;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Support\Collection;

class ReportService
{
    public function getLateRecords(string $from, string $to)
    {
        return AttendanceRecord::with(['employee.user', 'employee.department'])
            ->where('status', 'late')
            ->whereBetween('date', [$from, $to])
            ->orderBy('date', 'desc')
            ->get();
    }

    public function getDepartmentMonthlySummary(string $month): Collection
    {
        $year  = (int) substr($month, 0, 4);
        $m     = (int) substr($month, 5, 2);

        $departments = Department::with(['employees.user', 'employees.attendanceRecords' => function ($q) use ($year, $m) {
            $q->whereYear('date', $year)->whereMonth('date', $m);
        }])->get();

        return $departments->map(function ($dept) {
            $employees = $dept->employees;

            $present = 0; $late = 0; $absent = 0; $leave = 0; $workMinutes = 0;

            foreach ($employees as $emp) {
                foreach ($emp->attendanceRecords as $r) {
                    if (in_array($r->status, ['present', 'late'])) $present++;
                    if ($r->status === 'late') $late++;
                    if ($r->status === 'absent') $absent++;
                    if ($r->status === 'leave') $leave++;
                    $workMinutes += (int) $r->work_minutes;
                }
            }

            return [
                'department'        => $dept->name_ar ?? $dept->name,
                'employees_count'   => $employees->count(),
                'present_days'      => $present,
                'late_days'         => $late,
                'absent_days'       => $absent,
                'leave_days'        => $leave,
                'total_work_hours'  => round($workMinutes / 60, 1),
            ];
        });
    }

    public function getEmployeeMonthlySummary(int $employeeId, string $month): array
    {
        $employee = Employee::with(['user', 'department', 'shift'])->findOrFail($employeeId);

        $year  = (int) substr($month, 0, 4);
        $m     = (int) substr($month, 5, 2);

        $records = AttendanceRecord::where('employee_id', $employeeId)
            ->whereYear('date', $year)
            ->whereMonth('date', $m)
            ->orderBy('date')
            ->get();

        $summary = [
            'present'          => $records->whereIn('status', ['present', 'late'])->count(),
            'absent'           => $records->where('status', 'absent')->count(),
            'late'             => $records->where('status', 'late')->count(),
            'leave'            => $records->where('status', 'leave')->count(),
            'total_work_hours' => round(($records->sum('work_minutes') ?? 0) / 60, 1),
        ];

        return compact('employee', 'records', 'summary');
    }
}