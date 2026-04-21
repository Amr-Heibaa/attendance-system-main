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
                <th>المستخدم</th>
                <th>العملية</th>
                <th>نوع العنصر</th>
                <th>العنصر</th>
                <th>الوصف</th>
                <th>التاريخ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
                <tr>
                    <td>{{ $row->user?->name ?? 'النظام' }}</td>
                    <td>{{ $actions[$row->action] ?? $row->action }}</td>
                    <td>{{ $targetTypes[$row->target_type] ?? ($row->target_type ?? '-') }}</td>
                    <td>{{ $row->target_name ?? '-' }}</td>
                    <td>{{ $row->description ?? '-' }}</td>
                    <td>{{ $row->created_at?->format('Y/m/d H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>