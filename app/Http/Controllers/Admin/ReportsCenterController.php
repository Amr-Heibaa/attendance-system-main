<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\AuditLog;
use App\Models\CompOffRequest;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\MissionRequest;
use App\Models\PermissionRequest;
use App\Models\ShiftChangeRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Services\FinancialPolicyService;

class ReportsCenterController extends Controller
{
    private function statusLabel($status)
    {
        return match ($status) {
            'present' => 'حاضر',
            'late' => 'متأخر',
            'absent' => 'غائب',
            'leave' => 'إجازة',
            'holiday' => 'عطلة',
            'incomplete' => 'غير مكتمل',
            default => $status,
        };
    }

    private function applyEmployeeSearchToEmployeeQuery($query, ?string $search)
    {
        return $query->when($search, function ($q) use ($search) {
            $q->where(function ($subQ) use ($search) {
                $subQ->where('employee_code', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQ) use ($search) {
                        $userQ->where('name', 'like', "%{$search}%");
                    });
            });
        });
    }

    private function applyEmployeeSearchToRelatedEmployeeQuery($query, ?string $search)
    {
        return $query->when($search, function ($q) use ($search) {
            $q->whereHas('employee', function ($employeeQ) use ($search) {
                $employeeQ->where(function ($subQ) use ($search) {
                    $subQ->where('employee_code', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQ) use ($search) {
                            $userQ->where('name', 'like', "%{$search}%");
                        });
                });
            });
        });
    }

    private function getMonthlyLeaves(Request $request)
    {
        $query = LeaveRequest::with(['employee.user', 'employee.department'])
            ->where('status', 'approved');

        $this->applyEmployeeSearchToRelatedEmployeeQuery($query, $request->search);

        if ($request->from) {
            $query->whereDate('start_date', '>=', $request->from);
        }

        if ($request->to) {
            $query->whereDate('end_date', '<=', $request->to);
        }

        if ($request->employee_id) {
            $query->where('employee_id', $request->employee_id);
        }

        if (auth()->user()->hasRole('manager')) {
            $deptId = auth()->user()->employee?->department_id;
            $query->whereHas('employee', fn($q) => $q->where('department_id', $deptId));
        }

        return $query->get()
            ->groupBy(function ($item) {
                return $item->employee_id . '-' . $item->start_date->format('Y-m');
            })
            ->map(function ($group) {
                $first = $group->first();

                return [
                    'employee_name' => $first->employee->user->name ?? '-',
                    'department_name' => $first->employee->department->name_ar
                        ?? $first->employee->department->name
                        ?? '-',
                    'month' => $first->start_date->format('Y-m'),
                    'days' => $group->sum('days_count'),
                    'requests_count' => $group->count(),
                ];
            })
            ->values();
    }

    public function index(Request $request)
    {
        $reportType = $request->get('report_type', 'summary');
        $from = $request->get('from');
        $to = $request->get('to');
        $search = $request->get('search');

        $rows = collect();
        $summary = [];

        if ($reportType === 'summary') {
            $rows = $this->buildSummaryReport($from, $to, $search);
        }

        if ($reportType === 'detailed') {
            $query = AttendanceRecord::with(['employee.user', 'employee.department'])
                ->latest('date');

            $this->applyEmployeeSearchToRelatedEmployeeQuery($query, $search);

            if ($request->employee_id) {
                $query->where('employee_id', $request->employee_id);
            }

            if ($from) {
                $query->whereDate('date', '>=', $from);
            }

            if ($to) {
                $query->whereDate('date', '<=', $to);
            }

            if (!$from && !$to) {
                $query->whereDate('date', '>=', now()->startOfMonth()->toDateString())
                    ->whereDate('date', '<=', now()->endOfMonth()->toDateString());
            }

            if (auth()->user()->hasRole('manager')) {
                $deptId = auth()->user()->employee?->department_id;
                $query->whereHas('employee', fn($q) => $q->where('department_id', $deptId));
            }

            $rows = $query->get();
        }

        if ($reportType === 'monthly_leaves') {
            $rows = $this->getMonthlyLeaves($request);
        }

        if ($reportType === 'daily') {
            $query = AttendanceRecord::with(['employee.user', 'employee.department'])
                ->latest('date');

            $this->applyEmployeeSearchToRelatedEmployeeQuery($query, $search);

            if ($request->employee_id) {
                $query->where('employee_id', $request->employee_id);
            }

            if ($from) {
                $query->whereDate('date', '>=', $from);
            }

            if ($to) {
                $query->whereDate('date', '<=', $to);
            }

            if (!$from && !$to) {
                $query->whereDate('date', now()->toDateString());
            }

            if (auth()->user()->hasRole('manager')) {
                $deptId = auth()->user()->employee?->department_id;
                $query->whereHas('employee', fn($q) => $q->where('department_id', $deptId));
            }

            $rows = $query->get();
        }

        if ($reportType === 'leaves') {
            $query = LeaveRequest::with(['employee.user', 'leaveType', 'approvedBy'])
                ->latest();

            $this->applyEmployeeSearchToRelatedEmployeeQuery($query, $search);

            if ($request->employee_id) {
                $query->where('employee_id', $request->employee_id);
            }

            if ($from) {
                $query->whereDate('start_date', '>=', $from);
            }

            if ($to) {
                $query->whereDate('end_date', '<=', $to);
            }

            if (auth()->user()->hasRole('manager')) {
                $deptId = auth()->user()->employee?->department_id;
                $query->whereHas('employee', fn($q) => $q->where('department_id', $deptId));
            }

            $rows = $query->get();
        }

        if ($reportType === 'audit') {
            $query = AuditLog::with('user')->latest();

            if ($from) {
                $query->whereDate('created_at', '>=', $from);
            }

            if ($to) {
                $query->whereDate('created_at', '<=', $to);
            }

            $rows = $query->get();
        }

        $employeesQuery = Employee::with('user')->where('status', 'active');

        $this->applyEmployeeSearchToEmployeeQuery($employeesQuery, $search);

        if (auth()->user()->hasRole('manager')) {
            $deptId = auth()->user()->employee?->department_id;
            $employeesQuery->where('department_id', $deptId);
        }

        $employees = $employeesQuery->get();

        return view('admin.reports.index', compact('rows', 'summary', 'reportType', 'from', 'to', 'employees', 'search'));
    }

    public function exportExcel(Request $request)
    {
        $reportType = $request->get('report_type', 'summary');
        $from = $request->get('from');
        $to = $request->get('to');
        $search = $request->get('search');

        if ($reportType === 'summary') {
            $data = $this->buildSummaryReport($from, $to, $search)->map(function ($row) {
                return [
                    'كود الموظف' => $row['employee_code'],
                    'الموظف' => $row['employee_name'],
                    'القسم' => $row['department_name'],
                    'الحضور' => $row['present_count'],
                    'التأخير' => $row['late_count'],
                    'الغياب' => $row['absent_count'],
                    'الإجازات' => $row['leave_count'],
                    'ساعات العمل' => $row['work_hours'],
                    'الأذون' => $row['permissions_count'],
                    'المهمات' => $row['missions_count'],
                    'بدل الراحة' => $row['comp_off_count'],
                    'تغيير الوردية' => $row['shift_changes_count'],
                ];
            });

            return (new FastExcel($data))->download('summary-report.xlsx');
        }

        if ($reportType === 'monthly_leaves') {
            $data = $this->getMonthlyLeaves($request)->map(function ($row) {
                return [
                    'كود الموظف' => $row['employee_code'],
                    'الموظف' => $row['employee_name'],
                    'القسم' => $row['department_name'],
                    'الشهر' => $row['month'],
                    'عدد مرات الإجازة' => $row['requests_count'],
                    'عدد أيام الإجازة' => $row['days'],
                ];
            });

            return (new FastExcel($data))->download('monthly-leaves-report.xlsx');
        }

        if ($reportType === 'detailed') {
            $query = AttendanceRecord::with(['employee.user', 'employee.department'])
                ->latest('date');

            $this->applyEmployeeSearchToRelatedEmployeeQuery($query, $search);

            if ($from) {
                $query->whereDate('date', '>=', $from);
            }

            if ($to) {
                $query->whereDate('date', '<=', $to);
            }

            if (!$from && !$to) {
                $query->whereDate('date', '>=', now()->startOfMonth()->toDateString())
                    ->whereDate('date', '<=', now()->endOfMonth()->toDateString());
            }

            if (auth()->user()->hasRole('manager')) {
                $deptId = auth()->user()->employee?->department_id;
                $query->whereHas('employee', fn($q) => $q->where('department_id', $deptId));
            }

            $days = [
                'Sunday' => 'الأحد',
                'Monday' => 'الاثنين',
                'Tuesday' => 'الثلاثاء',
                'Wednesday' => 'الأربعاء',
                'Thursday' => 'الخميس',
                'Friday' => 'الجمعة',
                'Saturday' => 'السبت',
            ];

            $data = $query->get()->map(function ($row) use ($days) {
                return [
                    'الموظف' => $row->employee->user->name ?? '-',
                    'كود الموظف' => $row->employee->employee_code ?? '-',
                    'القسم' => $row->employee->department->name_ar ?? $row->employee->department->name ?? '-',
                    'التاريخ' => $row->date?->format('Y-m-d'),
                    'اليوم' => $days[$row->date?->format('l')] ?? '-',
                    'الحضور' => $row->check_in?->format('H:i') ?? '-',
                    'الانصراف' => $row->check_out?->format('H:i') ?? '-',
                    'الحالة' => $this->statusLabel($row->status),
                    'دقائق التأخير' => $row->late_minutes ?? 0,
                    'ساعات العمل' => round(($row->work_minutes ?? 0) / 60, 1),
                    'ملاحظات' => $row->notes ?? '-',
                ];
            });

            return (new FastExcel($data))->download('detailed-report.xlsx');
        }

        if ($reportType === 'daily') {
            $query = AttendanceRecord::with(['employee.user', 'employee.department'])
                ->latest('date');

            $this->applyEmployeeSearchToRelatedEmployeeQuery($query, $search);

            if ($from) {
                $query->whereDate('date', '>=', $from);
            }

            if ($to) {
                $query->whereDate('date', '<=', $to);
            }

            if (!$from && !$to) {
                $query->whereDate('date', now()->toDateString());
            }

            if (auth()->user()->hasRole('manager')) {
                $deptId = auth()->user()->employee?->department_id;
                $query->whereHas('employee', fn($q) => $q->where('department_id', $deptId));
            }

            $data = $query->get()->map(function ($row) {
                return [
                    'الموظف' => $row->employee->user->name ?? '-',
                    'كود الموظف' => $row->employee->employee_code ?? '-',
                    'القسم' => $row->employee->department->name_ar ?? $row->employee->department->name ?? '-',
                    'التاريخ' => $row->date?->format('Y-m-d'),
                    'الحضور' => $row->check_in?->format('H:i') ?? '-',
                    'الانصراف' => $row->check_out?->format('H:i') ?? '-',
                    'الحالة' => $this->statusLabel($row->status),
                    'دقائق التأخير' => $row->late_minutes ?? 0,
                    'ساعات العمل' => round(($row->work_minutes ?? 0) / 60, 1),
                ];
            });

            return (new FastExcel($data))->download('daily-report.xlsx');
        }

        if ($reportType === 'leaves') {
            $query = LeaveRequest::with(['employee.user', 'leaveType', 'approvedBy'])
                ->latest();

            $this->applyEmployeeSearchToRelatedEmployeeQuery($query, $search);

            if ($from) {
                $query->whereDate('start_date', '>=', $from);
            }

            if ($to) {
                $query->whereDate('end_date', '<=', $to);
            }

            if (auth()->user()->hasRole('manager')) {
                $deptId = auth()->user()->employee?->department_id;
                $query->whereHas('employee', fn($q) => $q->where('department_id', $deptId));
            }

            $data = $query->get()->map(function ($row) {
                return [
                    'الموظف' => $row->employee->user->name ?? '-',
                    'كود الموظف' => $row->employee->employee_code ?? '-',
                    'نوع الإجازة' => $row->leaveType->name_ar ?? $row->leaveType->name ?? '-',
                    'من تاريخ' => $row->start_date?->format('Y-m-d'),
                    'إلى تاريخ' => $row->end_date?->format('Y-m-d'),
                    'عدد الأيام' => $row->days_count,
                    'الحالة' => $row->status_label ?? $row->status,
                    'المعتمد بواسطة' => $row->approvedBy->name ?? '-',
                    'ملاحظات' => $row->manager_notes ?? '-',
                ];
            });

            return (new FastExcel($data))->download('leave-report.xlsx');
        }

        if ($reportType === 'audit') {
            $actions = [
                'approve_leave_request' => 'اعتماد إجازة',
                'reject_leave_request' => 'رفض إجازة',
                'approve_permission' => 'اعتماد إذن',
                'reject_permission' => 'رفض إذن',
                'approve_mission' => 'اعتماد مهمة',
                'reject_mission' => 'رفض مهمة',
                'approve_comp_off' => 'اعتماد بدل راحة',
                'reject_comp_off' => 'رفض بدل راحة',
                'approve_shift_change' => 'اعتماد تغيير وردية',
                'reject_shift_change' => 'رفض تغيير وردية',
            ];

            $targetTypes = [
                'leave_request' => 'طلب إجازة',
                'permission' => 'إذن',
                'mission' => 'مهمة',
                'comp_off' => 'بدل راحة',
                'shift_change' => 'تغيير وردية',
            ];

            $query = AuditLog::with('user')->latest();

            if ($from) {
                $query->whereDate('created_at', '>=', $from);
            }

            if ($to) {
                $query->whereDate('created_at', '<=', $to);
            }

            $data = $query->get()->map(function ($row) use ($actions, $targetTypes) {
                return [
                    'المستخدم' => $row->user?->name ?? 'النظام',
                    'العملية' => $actions[$row->action] ?? $row->action,
                    'نوع العنصر' => $targetTypes[$row->target_type] ?? ($row->target_type ?? '-'),
                    'العنصر' => $row->target_name ?? '-',
                    'الوصف' => $row->description ?? '-',
                    'التاريخ' => $row->created_at?->format('Y-m-d H:i'),
                ];
            });

            return (new FastExcel($data))->download('audit-report.xlsx');
        }

        return back()->with('error', 'هذا النوع من التقارير غير متاح للتصدير حاليًا');
    }

    public function exportPdf(Request $request)
    {
        $reportType = $request->get('report_type', 'summary');
        $from = $request->get('from');
        $to = $request->get('to');
        $search = $request->get('search');

        if ($reportType === 'summary') {
            $rows = $this->buildSummaryReport($from, $to, $search);

            $pdf = Pdf::loadView('admin.reports.pdf.summary', [
                'rows' => $rows,
                'from' => $from,
                'to' => $to,
                'title' => 'التقرير الإجمالي',
            ])->setPaper('a4', 'landscape');

            return $pdf->download('summary-report.pdf');
        }

        if ($reportType === 'monthly_leaves') {
            $rows = $this->getMonthlyLeaves($request);

            $pdf = Pdf::loadView('admin.reports.pdf.monthly-leaves', [
                'rows' => $rows,
                'from' => $request->from,
                'to' => $request->to,
                'title' => 'تقرير الإجازات الشهرية',
            ])->setPaper('a4', 'landscape');

            return $pdf->download('monthly-leaves-report.pdf');
        }

        if ($reportType === 'detailed') {
            $query = AttendanceRecord::with(['employee.user', 'employee.department'])
                ->latest('date');

            $this->applyEmployeeSearchToRelatedEmployeeQuery($query, $search);

            if ($from) {
                $query->whereDate('date', '>=', $from);
            }

            if ($to) {
                $query->whereDate('date', '<=', $to);
            }

            if (!$from && !$to) {
                $query->whereDate('date', '>=', now()->startOfMonth()->toDateString())
                    ->whereDate('date', '<=', now()->endOfMonth()->toDateString());
            }

            if (auth()->user()->hasRole('manager')) {
                $deptId = auth()->user()->employee?->department_id;
                $query->whereHas('employee', fn($q) => $q->where('department_id', $deptId));
            }

            $rows = $query->get();

            $pdf = Pdf::loadView('admin.reports.pdf.detailed', [
                'rows' => $rows,
                'from' => $from,
                'to' => $to,
                'title' => 'التقرير التفصيلي',
            ])->setPaper('a4', 'landscape');

            return $pdf->download('detailed-report.pdf');
        }

        if ($reportType === 'daily') {
            $query = AttendanceRecord::with(['employee.user', 'employee.department'])
                ->latest('date');

            $this->applyEmployeeSearchToRelatedEmployeeQuery($query, $search);

            if ($from) {
                $query->whereDate('date', '>=', $from);
            }

            if ($to) {
                $query->whereDate('date', '<=', $to);
            }

            if (!$from && !$to) {
                $query->whereDate('date', now()->toDateString());
            }

            if (auth()->user()->hasRole('manager')) {
                $deptId = auth()->user()->employee?->department_id;
                $query->whereHas('employee', fn($q) => $q->where('department_id', $deptId));
            }

            $rows = $query->get();

            $pdf = Pdf::loadView('admin.reports.pdf.daily', [
                'rows' => $rows,
                'from' => $from,
                'to' => $to,
                'title' => 'التقرير اليومي',
            ])->setPaper('a4', 'landscape');

            return $pdf->download('daily-report.pdf');
        }

        if ($reportType === 'leaves') {
            $query = LeaveRequest::with(['employee.user', 'leaveType', 'approvedBy'])
                ->latest();

            $this->applyEmployeeSearchToRelatedEmployeeQuery($query, $search);

            if ($from) {
                $query->whereDate('start_date', '>=', $from);
            }

            if ($to) {
                $query->whereDate('end_date', '<=', $to);
            }

            if (auth()->user()->hasRole('manager')) {
                $deptId = auth()->user()->employee?->department_id;
                $query->whereHas('employee', fn($q) => $q->where('department_id', $deptId));
            }

            $rows = $query->get();

            $pdf = Pdf::loadView('admin.reports.pdf.leaves', [
                'rows' => $rows,
                'from' => $from,
                'to' => $to,
                'title' => 'تقرير الإجازات',
            ])->setPaper('a4', 'landscape');

            return $pdf->download('leave-report.pdf');
        }

        if ($reportType === 'audit') {
            $actions = [
                'approve_leave_request' => 'اعتماد إجازة',
                'reject_leave_request' => 'رفض إجازة',
                'approve_permission' => 'اعتماد إذن',
                'reject_permission' => 'رفض إذن',
                'approve_mission' => 'اعتماد مهمة',
                'reject_mission' => 'رفض مهمة',
                'approve_comp_off' => 'اعتماد بدل راحة',
                'reject_comp_off' => 'رفض بدل راحة',
                'approve_shift_change' => 'اعتماد تغيير وردية',
                'reject_shift_change' => 'رفض تغيير وردية',
            ];

            $targetTypes = [
                'leave_request' => 'طلب إجازة',
                'permission' => 'إذن',
                'mission' => 'مهمة',
                'comp_off' => 'بدل راحة',
                'shift_change' => 'تغيير وردية',
            ];

            $query = AuditLog::with('user')->latest();

            if ($from) {
                $query->whereDate('created_at', '>=', $from);
            }

            if ($to) {
                $query->whereDate('created_at', '<=', $to);
            }

            $rows = $query->get();

            $pdf = Pdf::loadView('admin.reports.pdf.audit', [
                'rows' => $rows,
                'from' => $from,
                'to' => $to,
                'title' => 'تقرير التعديلات',
                'actions' => $actions,
                'targetTypes' => $targetTypes,
            ])->setPaper('a4', 'landscape');

            return $pdf->download('audit-report.pdf');
        }

        return back()->with('error', 'هذا النوع من التقارير غير متاح للتصدير حاليًا');
    }

    private function buildSummaryReport(?string $from, ?string $to, ?string $search = null): \Illuminate\Support\Collection
    {
        $employeesQuery = Employee::with(['user', 'department'])
            ->where('status', 'active');

        $this->applyEmployeeSearchToEmployeeQuery($employeesQuery, $search);

        if (auth()->user()->hasRole('manager')) {
            $deptId = auth()->user()->employee?->department_id;
            $employeesQuery->where('department_id', $deptId);
        }

        $employees = $employeesQuery->get();

        return $employees->map(function ($employee) use ($from, $to) {
            $attendanceQuery = AttendanceRecord::where('employee_id', $employee->id);
            $leaveQuery = LeaveRequest::where('employee_id', $employee->id);
            $permissionQuery = PermissionRequest::where('employee_id', $employee->id);
            $missionQuery = MissionRequest::where('employee_id', $employee->id);
            $compOffQuery = CompOffRequest::where('employee_id', $employee->id);
            $shiftChangeQuery = ShiftChangeRequest::where('employee_id', $employee->id);

            if ($from) {
                $attendanceQuery->whereDate('date', '>=', $from);
                $leaveQuery->whereDate('start_date', '>=', $from);
                $permissionQuery->whereDate('date', '>=', $from);
                $missionQuery->whereDate('date', '>=', $from);
                $compOffQuery->whereDate('worked_on_date', '>=', $from);
                $shiftChangeQuery->whereDate('effective_date', '>=', $from);
            }

            if ($to) {
                $attendanceQuery->whereDate('date', '<=', $to);
                $leaveQuery->whereDate('end_date', '<=', $to);
                $permissionQuery->whereDate('date', '<=', $to);
                $missionQuery->whereDate('date', '<=', $to);
                $compOffQuery->whereDate('worked_on_date', '<=', $to);
                $shiftChangeQuery->whereDate('effective_date', '<=', $to);
            }

            return [
                'employee_name' => $employee->user->name ?? '-',
                'employee_code' => $employee->employee_code ?? '-',
                'department_name' => $employee->department->name_ar ?? '-',
                'present_count' => (clone $attendanceQuery)->where('status', 'present')->count(),
                'late_count' => (clone $attendanceQuery)->where('status', 'late')->count(),
                'absent_count' => (clone $attendanceQuery)->where('status', 'absent')->count(),
                'leave_count' => (clone $attendanceQuery)->where('status', 'leave')->count(),
                'work_hours' => round(((clone $attendanceQuery)->sum('work_minutes')) / 60, 1),
                'permissions_count' => (clone $permissionQuery)->count(),
                'missions_count' => (clone $missionQuery)->count(),
                'comp_off_count' => (clone $compOffQuery)->count(),
                'shift_changes_count' => (clone $shiftChangeQuery)->count(),
            ];
        });
    }
}
