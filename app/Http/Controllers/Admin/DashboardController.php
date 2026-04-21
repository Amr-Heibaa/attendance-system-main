<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\CompOffRequest;
use App\Models\LeaveRequest;
use App\Models\MissionRequest;
use App\Models\PermissionRequest;
use App\Models\ShiftChangeRequest;
use App\Services\AttendanceService;

class DashboardController extends Controller
{
    public function __construct(private AttendanceService $attendanceService) {}

    public function index()
    {
        $stats = $this->attendanceService->getTodayStats();
        $trend = $this->attendanceService->getAttendanceTrend(30);
        $departmentStats = $this->attendanceService->getDepartmentStats();

        $today = now()->toDateString();

        $deptId = auth()->user()->hasRole('manager')
            ? auth()->user()->employee?->department_id
            : null;

        // آخر التنبيهات
        $latestAlerts = collect();

        $lateAlerts = AttendanceRecord::with(['employee.user'])
            ->when($deptId, fn($q) => $q->whereHas('employee', fn($e) => $e->where('department_id', $deptId)))
            ->whereDate('date', $today)
            ->where('status', 'late')
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($record) {
                return [
                    'type' => 'late',
                    'title' => 'تأخير موظف',
                    'message' => ($record->employee->user->name ?? 'موظف') . ' تأخر ' . ($record->late_minutes ?? 0) . ' دقيقة اليوم',
                ];
            });

        $incompleteAlerts = AttendanceRecord::with(['employee.user'])
            ->when($deptId, fn($q) => $q->whereHas('employee', fn($e) => $e->where('department_id', $deptId)))
            ->whereDate('date', $today)
            ->whereNotNull('check_in')
            ->whereNull('check_out')
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($record) {
                return [
                    'type' => 'incomplete',
                    'title' => 'انصراف غير مكتمل',
                    'message' => ($record->employee->user->name ?? 'موظف') . ' سجل حضورًا بدون انصراف',
                ];
            });

        $latestAlerts = $lateAlerts
            ->concat($incompleteAlerts)
            ->take(6)
            ->values();

        // آخر الطلبات المعلقة
        $leaveRequests = LeaveRequest::with(['employee.user'])
            ->when($deptId, fn($q) => $q->whereHas('employee', fn($e) => $e->where('department_id', $deptId)))
            ->where('status', 'pending')
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($row) {
                return [
                    'employee_name' => $row->employee->user->name ?? '-',
                    'request_type' => 'طلب إجازة',
                    'date' => $row->created_at?->format('Y-m-d') ?? '-',
                ];
            });

        $permissionRequests = PermissionRequest::with(['employee.user'])
            ->when($deptId, fn($q) => $q->whereHas('employee', fn($e) => $e->where('department_id', $deptId)))
            ->where('status', 'pending')
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($row) {
                return [
                    'employee_name' => $row->employee->user->name ?? '-',
                    'request_type' => 'طلب إذن',
                    'date' => $row->created_at?->format('Y-m-d') ?? '-',
                ];
            });

        $missionRequests = MissionRequest::with(['employee.user'])
            ->when($deptId, fn($q) => $q->whereHas('employee', fn($e) => $e->where('department_id', $deptId)))
            ->where('status', 'pending')
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($row) {
                return [
                    'employee_name' => $row->employee->user->name ?? '-',
                    'request_type' => 'طلب مهمة',
                    'date' => $row->created_at?->format('Y-m-d') ?? '-',
                ];
            });

        $compOffRequests = CompOffRequest::with(['employee.user'])
            ->when($deptId, fn($q) => $q->whereHas('employee', fn($e) => $e->where('department_id', $deptId)))
            ->where('status', 'pending')
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($row) {
                return [
                    'employee_name' => $row->employee->user->name ?? '-',
                    'request_type' => 'طلب بدل راحة',
                    'date' => $row->created_at?->format('Y-m-d') ?? '-',
                ];
            });

        $shiftChangeRequests = ShiftChangeRequest::with(['employee.user'])
            ->when($deptId, fn($q) => $q->whereHas('employee', fn($e) => $e->where('department_id', $deptId)))
            ->where('status', 'pending')
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($row) {
                return [
                    'employee_name' => $row->employee->user->name ?? '-',
                    'request_type' => 'طلب تغيير وردية',
                    'date' => $row->created_at?->format('Y-m-d') ?? '-',
                ];
            });

        $pendingRequests = $leaveRequests
            ->concat($permissionRequests)
            ->concat($missionRequests)
            ->concat($compOffRequests)
            ->concat($shiftChangeRequests)
            ->sortByDesc('date')
            ->take(8)
            ->values();

        return view('admin.dashboard', compact(
            'stats',
            'trend',
            'departmentStats',
            'latestAlerts',
            'pendingRequests'
        ));
    }
}