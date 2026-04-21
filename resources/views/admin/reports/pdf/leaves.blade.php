<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; direction: rtl; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: right; }
        th { background: #f3f4f6; }
        h2 { text-align: center; margin-bottom: 10px; }
        .meta { text-align: center; color: #666; margin-bottom: 10px; }
    </style>
</head>
<body>
    <h2>{{ $title }}</h2>
    <div class="meta">
        من: {{ $from ?: '-' }} | إلى: {{ $to ?: '-' }}
    </div>

    <table>
        <thead>
            <tr>
                <th>الموظف</th>
                <th>نوع الإجازة</th>
                <th>من تاريخ</th>
                <th>إلى تاريخ</th>
                <th>عدد الأيام</th>
                <th>الحالة</th>
                <th>المعتمد بواسطة</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
                <tr>
                    <td>{{ $row->employee->user->name ?? '-' }}</td>
                    <td>{{ $row->leaveType->name_ar ?? $row->leaveType->name ?? '-' }}</td>
                    <td>{{ $row->start_date?->format('Y/m/d') }}</td>
                    <td>{{ $row->end_date?->format('Y/m/d') }}</td>
                    <td>{{ $row->days_count }}</td>
                    <td>{{ $row->status_label ?? $row->status }}</td>
                    <td>{{ $row->approvedBy->name ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>