<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompOffRequest;
use App\Models\LeaveRequest;
use App\Models\MissionRequest;
use App\Models\PermissionRequest;
use App\Models\ShiftChangeRequest;

class RequestCenterController extends Controller
{
    public function index()
    {
        $deptId = auth()->user()->hasRole('manager')
            ? auth()->user()->employee?->department_id
            : null;

        $leaveRequests = LeaveRequest::query()
            ->when($deptId, fn($q) => $q->whereHas('employee', fn($e) => $e->where('department_id', $deptId)));

        $permissionRequests = PermissionRequest::query()
            ->when($deptId, fn($q) => $q->whereHas('employee', fn($e) => $e->where('department_id', $deptId)));

        $missionRequests = MissionRequest::query()
            ->when($deptId, fn($q) => $q->whereHas('employee', fn($e) => $e->where('department_id', $deptId)));

        $compOffRequests = CompOffRequest::query()
            ->when($deptId, fn($q) => $q->whereHas('employee', fn($e) => $e->where('department_id', $deptId)));

        $shiftChangeRequests = ShiftChangeRequest::query()
            ->when($deptId, fn($q) => $q->whereHas('employee', fn($e) => $e->where('department_id', $deptId)));

        $summary = [
            [
                'name' => 'الإجازات',
                'count' => (clone $leaveRequests)->count(),
                'pending_count' => (clone $leaveRequests)->where('status', 'pending')->count(),
                'status' => 'مفعلة',
                'route' => route('admin.leave-requests.index'),
            ],
            [
                'name' => 'الأذون',
                'count' => (clone $permissionRequests)->count(),
                'pending_count' => (clone $permissionRequests)->where('status', 'pending')->count(),
                'status' => 'مفعلة',
                'route' => route('admin.permission-requests.index'),
            ],
            [
                'name' => 'المهمات',
                'count' => (clone $missionRequests)->count(),
                'pending_count' => (clone $missionRequests)->where('status', 'pending')->count(),
                'status' => 'مفعلة',
                'route' => route('admin.mission-requests.index'),
            ],
            [
                'name' => 'بدل الراحة',
                'count' => (clone $compOffRequests)->count(),
                'pending_count' => (clone $compOffRequests)->where('status', 'pending')->count(),
                'status' => 'مفعلة',
                'route' => route('admin.comp-off-requests.index'),
            ],
            [
                'name' => 'تغيير الوردية',
                'count' => (clone $shiftChangeRequests)->count(),
                'pending_count' => (clone $shiftChangeRequests)->where('status', 'pending')->count(),
                'status' => 'مفعلة',
                'route' => route('admin.shift-change-requests.index'),
            ],
        ];

        return view('admin.requests.index', compact('summary'));
    }
}