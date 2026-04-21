<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; direction: rtl; font-size: 11px; }
        h2 { text-align: center; margin-bottom: 10px; }
        .meta { text-align: center; color: #666; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 7px; text-align: right; }
        th { background: #f3f4f6; }
    </style>
</head>
<body>
    @php
        $days = [
            'Sunday' => 'الأحد',
            'Monday' => 'الاثنين',
            'Tuesday' => 'الثلاثاء',
            'Wednesday' => 'الأربعاء',
            'Thursday' => 'الخميس',
            'Friday' => 'الجمعة',
            'Saturday' => 'السبت',
        ];

        $statusMap = [
            'present' => 'حاضر',
            'late' => 'متأخر',
            'absent' => 'غائب',
            'leave' => 'إجازة',
            'holiday' => 'عطلة',
            'incomplete' => 'غير مكتمل',
        ];
    @endphp

    <h2>{{ $title }}</h2>
    <div class="meta">
        من: {{ $from ?: '-' }} | إلى: {{ $to ?: '-' }}
    </div>

    <table>
        <thead>
            <tr>
                <th>الموظف</th>
                <th>القسم</th>
                <th>التاريخ</th>
                <th>اليوم</th>
                <th>الحضور</th>
                <th>الانصراف</th>
                <th>الحالة</th>
                <th>دقائق التأخير</th>
                <th>ساعات العمل</th>
                <th>ملاحظات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
                <tr>
                    <td>{{ $row->employee->user->name ?? '-' }}</td>
                    <td>{{ $row->employee->department->name_ar ?? $row->employee->department->name ?? '-' }}</td>
                    <td>{{ $row->date?->format('Y/m/d') }}</td>
                    <td>{{ $days[$row->date?->format('l')] ?? '-' }}</td>
                    <td>{{ $row->check_in?->format('H:i') ?? '-' }}</td>
                    <td>{{ $row->check_out?->format('H:i') ?? '-' }}</td>
                    <td>{{ $statusMap[$row->status] ?? $row->status }}</td>
                    <td>{{ $row->late_minutes ?? 0 }}</td>
                    <td>{{ round(($row->work_minutes ?? 0) / 60, 1) }}</td>
                    <td>{{ $row->notes ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>