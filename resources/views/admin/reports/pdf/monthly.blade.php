<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تقرير الحضور</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Cairo', sans-serif; direction: rtl; }
        body { margin: 20px; color: #333; font-size: 13px; background: white; }
        h1 { text-align: center; color: #1e40af; font-size: 22px; margin-bottom: 5px; }
        .subtitle { text-align: center; color: #666; margin-bottom: 15px; }
        .print-btn {
            display: block;
            margin: 0 auto 20px;
            background: #1e40af;
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            font-family: 'Cairo', sans-serif;
        }
        @media print { .print-btn { display: none; } }
        .info-box { background: #f8fafc; border: 1px solid #ddd; padding: 10px 15px; margin-bottom: 12px; border-radius: 6px; }
        .info-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; }
        .lbl { color: #888; font-size: 10px; }
        .val { font-weight: bold; font-size: 13px; }
        .summary-box { background: #eff6ff; border: 1px solid #bfdbfe; padding: 10px; margin-bottom: 15px; border-radius: 6px; text-align: center; }
        .sum-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 10px; margin-top: 8px; }
        .big { font-size: 24px; font-weight: bold; }
        .sm  { font-size: 10px; color: #666; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #1e40af; color: white; padding: 8px; text-align: right; font-size: 12px; }
        td { padding: 7px 8px; border-bottom: 1px solid #eee; font-size: 12px; }
        tr:nth-child(even) td { background: #f8fafc; }
        .c-green  { color: #16a34a; font-weight: bold; }
        .c-yellow { color: #d97706; font-weight: bold; }
        .c-red    { color: #dc2626; font-weight: bold; }
        .c-blue   { color: #2563eb; font-weight: bold; }
        .c-gray   { color: #6b7280; font-weight: bold; }
        .footer { text-align: center; color: #aaa; font-size: 10px; margin-top: 15px; }
    </style>
</head>
<body>

<button class="print-btn" onclick="window.print()">🖨️ طباعة / حفظ PDF</button>

<h1>تقرير الحضور الشهري</h1>
<p class="subtitle">{{ $employee->user->name }} | {{ $employee->employee_code }}</p>

<div class="info-box">
    <div class="info-grid">
        <div><div class="lbl">القسم</div><div class="val">{{ $employee->department?->name_ar ?? '-' }}</div></div>
        <div><div class="lbl">المسمى الوظيفي</div><div class="val">{{ $employee->job_title ?? '-' }}</div></div>
        <div><div class="lbl">الوردية</div><div class="val">{{ $employee->shift?->name ?? '-' }}</div></div>
        <div><div class="lbl">تاريخ التعيين</div><div class="val">{{ $employee->hire_date->format('Y/m/d') }}</div></div>
    </div>
</div>

<div class="summary-box">
    <div style="font-size:15px;font-weight:bold;color:#1e40af;">ملخص الشهر</div>
    <div class="sum-grid">
        <div><div class="big" style="color:#16a34a;">{{ $summary['present'] }}</div><div class="sm">أيام حضور</div></div>
        <div><div class="big" style="color:#dc2626;">{{ $summary['absent'] }}</div><div class="sm">أيام غياب</div></div>
        <div><div class="big" style="color:#d97706;">{{ $summary['late'] }}</div><div class="sm">أيام تأخير</div></div>
        <div><div class="big" style="color:#2563eb;">{{ $summary['leave'] }}</div><div class="sm">أيام إجازة</div></div>
        <div><div class="big" style="color:#7c3aed;">{{ $summary['total_work_hours'] }}</div><div class="sm">ساعات عمل</div></div>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>التاريخ</th><th>اليوم</th><th>وقت الحضور</th>
            <th>وقت الانصراف</th><th>ساعات العمل</th><th>التأخير</th><th>الحالة</th>
        </tr>
    </thead>
    <tbody>
    @php
    $days = ['Sunday'=>'الأحد','Monday'=>'الاثنين','Tuesday'=>'الثلاثاء','Wednesday'=>'الأربعاء','Thursday'=>'الخميس','Friday'=>'الجمعة','Saturday'=>'السبت'];
    $statusMap = ['present'=>['label'=>'حاضر','class'=>'c-green'],'late'=>['label'=>'متأخر','class'=>'c-yellow'],'absent'=>['label'=>'غائب','class'=>'c-red'],'leave'=>['label'=>'إجازة','class'=>'c-blue'],'holiday'=>['label'=>'عطلة','class'=>'c-gray']];
    @endphp
    @forelse($records as $r)
    <tr>
        <td>{{ $r->date->format('Y/m/d') }}</td>
        <td>{{ $days[$r->date->format('l')] ?? '-' }}</td>
        <td>{{ $r->check_in?->format('h:i A')  ?? '-' }}</td>
        <td>{{ $r->check_out?->format('h:i A') ?? '-' }}</td>
        <td>{{ $r->work_minutes > 0 ? round($r->work_minutes/60,1) : '-' }}</td>
        <td>{{ $r->late_minutes > 0 ? $r->late_minutes : '-' }}</td>
        <td class="{{ $statusMap[$r->status]['class'] ?? 'c-gray' }}">{{ $statusMap[$r->status]['label'] ?? $r->status }}</td>
    </tr>
    @empty
    <tr><td colspan="7" style="text-align:center;color:#999;">لا توجد سجلات</td></tr>
    @endforelse
    </tbody>
</table>

<div class="footer">تم إنشاء هذا التقرير في {{ now()->format('Y/m/d h:i A') }}</div>

</body>
</html>