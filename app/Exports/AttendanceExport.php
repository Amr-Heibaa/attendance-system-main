<?php

namespace App\Exports;

use App\Models\AttendanceRecord;

class AttendanceExport
{
    public function __construct(
        private int $employeeId,
        private string $month
    ) {}

    public function export(): \Illuminate\Support\Collection
    {
        $records = AttendanceRecord::where('employee_id', $this->employeeId)
            ->whereYear('date', substr($this->month, 0, 4))
            ->whereMonth('date', substr($this->month, 5, 2))
            ->orderBy('date')
            ->get();

        $days = [
            'Sunday'    => 'الاحد',
            'Monday'    => 'الاثنين',
            'Tuesday'   => 'الثلاثاء',
            'Wednesday' => 'الاربعاء',
            'Thursday'  => 'الخميس',
            'Friday'    => 'الجمعة',
            'Saturday'  => 'السبت',
        ];

        $statusLabels = [
            'present' => 'حاضر',
            'late'    => 'متاخر',
            'absent'  => 'غائب',
            'leave'   => 'اجازة',
            'holiday' => 'عطلة',
        ];

        return $records->map(function ($r) use ($days, $statusLabels) {
            return [
                'التاريخ'        => $r->date->format('Y/m/d'),
                'اليوم'          => $days[$r->date->format('l')] ?? '-',
                'وقت الحضور'    => $r->check_in?->format('h:i A')  ?? '-',
                'وقت الانصراف'  => $r->check_out?->format('h:i A') ?? '-',
                'ساعات العمل'   => $r->work_minutes > 0 ? round($r->work_minutes / 60, 1) : 0,
                'التاخير دقيقة' => $r->late_minutes ?? 0,
                'الحالة'         => $statusLabels[$r->status] ?? $r->status,
            ];
        });
    }
}