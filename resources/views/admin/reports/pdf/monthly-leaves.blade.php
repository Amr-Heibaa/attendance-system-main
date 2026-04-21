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
            <th>القسم</th>
            <th>الشهر</th>
            <th>عدد مرات الإجازة</th>
            <th>عدد أيام الإجازة</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rows as $row)
            <tr>
                <td>{{ $row['employee_name'] }}</td>
                <td>{{ $row['department_name'] }}</td>
                <td>{{ $row['month'] }}</td>
                <td>{{ $row['requests_count'] }}</td>
                <td>{{ $row['days'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>