<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\AttendanceExport;
use App\Models\AttendanceRecord;
use App\Models\Department;
use App\Models\Employee;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;

class ReportController extends Controller
{
    public function monthly(Request $request)
    {
        $employees = Employee::with('user')->where('status', 'active')->get();

        if (!$request->filled('employee_id')) {
            return view('admin.reports.monthly', compact('employees'));
        }

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month'       => 'required|date_format:Y-m',
        ]);

        $employee = Employee::with(['user', 'department', 'shift'])
                            ->findOrFail($request->employee_id);
        $month    = $request->month;

        $records = AttendanceRecord::where('employee_id', $employee->id)
            ->whereYear('date',  substr($month, 0, 4))
            ->whereMonth('date', substr($month, 5, 2))
            ->orderBy('date')
            ->get();

        $summary = [
            'present'          => $records->whereIn('status', ['present', 'late'])->count(),
            'absent'           => $records->where('status', 'absent')->count(),
            'late'             => $records->where('status', 'late')->count(),
            'leave'            => $records->where('status', 'leave')->count(),
            'total_work_hours' => round($records->sum('work_minutes') / 60, 1),
        ];

        return view('admin.reports.monthly',
            compact('employee', 'records', 'summary', 'employees', 'month'));
    }
    public function exportMonthlyPdf(Request $request)
{
    $request->validate([
        'employee_id' => 'required|exists:employees,id',
        'month'       => 'required|date_format:Y-m',
    ]);

    $employee = Employee::with(['user', 'department', 'shift'])
                        ->findOrFail($request->employee_id);

    $records = AttendanceRecord::where('employee_id', $employee->id)
        ->whereYear('date',  substr($request->month, 0, 4))
        ->whereMonth('date', substr($request->month, 5, 2))
        ->orderBy('date')
        ->get();

    $summary = [
        'present'          => $records->whereIn('status', ['present', 'late'])->count(),
        'absent'           => $records->where('status', 'absent')->count(),
        'late'             => $records->where('status', 'late')->count(),
        'leave'            => $records->where('status', 'leave')->count(),
        'total_work_hours' => round($records->sum('work_minutes') / 60, 1),
    ];

    // عرض HTML في المتصفح مع زر طباعة
    return view('admin.reports.pdf.monthly',
        compact('employee', 'records', 'summary'));
}
public function exportMonthlyExcel(Request $request)
{
    $request->validate([
        'employee_id' => 'required|exists:employees,id',
        'month'       => 'required|date_format:Y-m',
    ]);

    $employee = Employee::with(['user', 'department', 'shift'])->findOrFail($request->employee_id);

    $month = $request->month;
    $year  = (int) substr($month, 0, 4);
    $mon   = (int) substr($month, 5, 2);

    $records = AttendanceRecord::where('employee_id', $employee->id)
        ->whereYear('date', $year)
        ->whereMonth('date', $mon)
        ->orderBy('date')
        ->get();

    // ====== Summary rules ======
    $presentDays = $records->whereIn('status', ['present', 'late'])->count();
    $absentDays  = $records->where('status', 'absent')->count();
    $lateDays    = $records->where('status', 'late')->count();
    $leaveDays   = $records->where('status', 'leave')->count();
    $holidayDays = $records->where('status', 'holiday')->count();

    $incompleteDays = $records->filter(fn($r) => $r->check_in && !$r->check_out)->count();

    $totalWorkHours = round($records->sum('work_minutes') / 60, 2);
    $totalLateMins  = (int) $records->sum('late_minutes');

    $daysAr = [
        'Sunday'    => 'الأحد',
        'Monday'    => 'الاثنين',
        'Tuesday'   => 'الثلاثاء',
        'Wednesday' => 'الأربعاء',
        'Thursday'  => 'الخميس',
        'Friday'    => 'الجمعة',
        'Saturday'  => 'السبت',
    ];

    $statusLabel = [
        'present'  => 'حاضر',
        'late'     => 'متأخر',
        'absent'   => 'غائب',
        'leave'    => 'إجازة',
        'holiday'  => 'عطلة',
        'incomplete' => 'غير مكتمل',
    ];

    // ====== Build rows (Summary first) ======
    $rows = [];

    // عنوان
    $rows[] = ['التقرير' => 'تقرير الحضور الشهري'];
    $rows[] = ['التقرير' => "الموظف: {$employee->user->name} | كود: {$employee->employee_code} | الشهر: {$month}"];
    $rows[] = ['التقرير' => '']; // سطر فاضي

    // ملخص
    $rows[] = ['البند' => 'أيام الحضور',         'القيمة' => $presentDays];
    $rows[] = ['البند' => 'أيام الغياب',         'القيمة' => $absentDays];
    $rows[] = ['البند' => 'أيام التأخير',        'القيمة' => $lateDays];
    $rows[] = ['البند' => 'أيام الإجازة',        'القيمة' => $leaveDays];
    $rows[] = ['البند' => 'أيام العطلات',        'القيمة' => $holidayDays];
    $rows[] = ['البند' => 'أيام غير مكتملة',     'القيمة' => $incompleteDays];
    $rows[] = ['البند' => 'إجمالي ساعات العمل',  'القيمة' => $totalWorkHours];
    $rows[] = ['البند' => 'إجمالي دقائق التأخير','القيمة' => $totalLateMins];

    $rows[] = ['التقرير' => '']; // فاصل
    $rows[] = ['التقرير' => 'تفاصيل الأيام'];   // عنوان جزء التفاصيل

    // Header صف للتفاصيل (FastExcel هياخده من keys أول Row في التفاصيل)
    $rows[] = [
        'التاريخ' => 'التاريخ',
        'اليوم' => 'اليوم',
        'وقت الحضور' => 'وقت الحضور',
        'وقت الانصراف' => 'وقت الانصراف',
        'ساعات العمل' => 'ساعات العمل',
        'دقائق التأخير' => 'دقائق التأخير',
        'الحالة' => 'الحالة',
        'ملاحظات' => 'ملاحظات',
    ];

    foreach ($records as $r) {
        // weekend rule: الجمعة/السبت (لو عندك status holiday بالفعل سيبه، لو مش موجود ممكن نوسمه هنا)
        $date = $r->date instanceof Carbon ? $r->date : Carbon::parse($r->date);
        $dayEn = $date->format('l');
        $isWeekend = in_array($dayEn, ['Friday', 'Saturday'], true);

        // missing checkout => incomplete + hours=0
        $isIncomplete = $r->check_in && !$r->check_out;

        $workHours = 0;
        if (!$isIncomplete && (int)$r->work_minutes > 0) {
            $workHours = round($r->work_minutes / 60, 2);
        }

        $finalStatus = $r->status;
        $notes = $r->notes ?? '';

        if ($isIncomplete) {
            $finalStatus = 'incomplete';
            $notes = trim(($notes ? $notes.' | ' : '') . 'Missing checkout');
        }

        // لو تحب: نوسم الويك إند عطلة لو يوم جمعة/سبت ومش مسجل أصلاً كـ leave/holiday/absent
        if ($isWeekend && !in_array($finalStatus, ['leave', 'holiday'], true)) {
            // اختيارياً: سيبه كما هو لو انت بتسجل الويك إند في records كـ holiday
            // $finalStatus = 'holiday';
        }

        $rows[] = [
            'التاريخ' => $date->format('Y-m-d'),
            'اليوم' => $daysAr[$dayEn] ?? $dayEn,
            'وقت الحضور' => $r->check_in ? Carbon::parse($r->check_in)->format('H:i') : '-',
            'وقت الانصراف' => $r->check_out ? Carbon::parse($r->check_out)->format('H:i') : '-',
            'ساعات العمل' => $workHours,
            'دقائق التأخير' => (int) ($r->late_minutes ?? 0),
            'الحالة' => $statusLabel[$finalStatus] ?? $finalStatus,
            'ملاحظات' => $notes,
        ];
    }

    $filename = "attendance-{$employee->employee_code}-{$month}.xlsx";
    return (new FastExcel($rows))->download($filename);
}
    public function lateReport(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to   = $request->to   ?? now()->toDateString();

        $records = AttendanceRecord::with(['employee.user', 'employee.department'])
            ->where('status', 'late')
            ->whereBetween('date', [$from, $to])
            ->orderBy('date', 'desc')
            ->paginate(20);

        return view('admin.reports.late', compact('records', 'from', 'to'));
    }

    public function departmentReport(Request $request)
    {
        $month = $request->month ?? now()->format('Y-m');

        $departments = Department::with([
            'employees.attendanceRecords' => function ($q) use ($month) {
                $q->whereYear('date',  substr($month, 0, 4))
                  ->whereMonth('date', substr($month, 5, 2));
            },
            'employees.user',
        ])->get();

        return view('admin.reports.department', compact('departments', 'month'));
    }
}