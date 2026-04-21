<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            direction: rtl;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        .meta {
            text-align: center;
            color: #666;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: right;
        }

        th {
            background: #f3f4f6;
        }
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
                <th>أيام الحضور</th>
                <th>أيام التأخير</th>
                <th>أيام الغياب</th>
                <th>أيام الإجازات</th>
                <th>إجمالي ساعات العمل</th>
                <th>الأذون</th>
                <th>المهمات</th>
                <th>بدل الراحة</th>
                <th>تغيير الوردية</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
            <tr>
                <td>{{ $row['employee_name'] }}</td>
                <td>{{ $row['department_name'] }}</td>
                <td>{{ $row['present_count'] }}</td>
                <td>{{ $row['late_count'] }}</td>
                <td>{{ $row['absent_count'] }}</td>
                <td>{{ $row['leave_count'] }}</td>
                <td>{{ $row['work_hours'] }}</td>
                <td>{{ $row['permissions_count'] }}</td>
                <td>{{ $row['missions_count'] }}</td>
                <td>{{ $row['comp_off_count'] }}</td>
                <td>{{ $row['shift_changes_count'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>