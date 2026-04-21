<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PermissionRequest;
use Illuminate\Http\Request;
use App\Services\AuditService;
use App\Services\NotificationService;

class PermissionRequestController extends Controller
{
    public function __construct(
        private AuditService $auditService,
        private NotificationService $notificationService
    ) {}

    public function index(Request $request)
    {
        $query = PermissionRequest::with(['employee.user', 'employee.department'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest();

        if (auth()->user()->hasRole('manager')) {
            $deptId = auth()->user()->employee?->department_id;
            $query->whereHas('employee', fn($q) => $q->where('department_id', $deptId));
        }

        $requests = $query->paginate(15);

        return view('admin.permission-requests.index', compact('requests'));
    }

    public function show(PermissionRequest $permissionRequest)
    {
        if (auth()->user()->hasRole('manager')) {
            $deptId = auth()->user()->employee?->department_id;
            abort_if($permissionRequest->employee->department_id != $deptId, 403);
        }

        $permissionRequest->load(['employee.user', 'employee.department', 'approvedBy']);

        return view('admin.permission-requests.show', compact('permissionRequest'));
    }

    public function approve(Request $request, PermissionRequest $permissionRequest)
    {
        if (auth()->user()->hasRole('manager')) {
            $deptId = auth()->user()->employee?->department_id;
            abort_if($permissionRequest->employee->department_id != $deptId, 403);
        }

        $request->validate([
            'manager_notes' => 'nullable|string|max:500',
        ]);

        if ($permissionRequest->status !== 'pending') {
            return back()->with('error', 'تمت معالجة هذا الطلب مسبقاً');
        }

        $permissionRequest->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'manager_notes' => $request->manager_notes,
        ]);
        $this->notificationService->create(
            $permissionRequest->employee->user_id,
            'permission',
            'تم اعتماد الإذن',
            'تمت الموافقة على طلب الإذن الخاص بك'
        );

        $this->auditService->log(
            action: 'approve_permission',
            targetType: 'permission',
            targetId: $permissionRequest->id,
            targetName: $permissionRequest->employee->user->name ?? null,
            description: 'تم اعتماد إذن',
            meta: [
                'employee_id' => $permissionRequest->employee_id,
                'date' => $permissionRequest->date?->format('Y-m-d') ?? null,
                'from' => $permissionRequest->from_time ?? null,
                'to' => $permissionRequest->to_time ?? null,
            ]
        );

        return redirect()->route('admin.permission-requests.index')
            ->with('success', 'تم اعتماد الإذن بنجاح');
    }

    public function reject(Request $request, PermissionRequest $permissionRequest)
    {
        if (auth()->user()->hasRole('manager')) {
            $deptId = auth()->user()->employee?->department_id;
            abort_if($permissionRequest->employee->department_id != $deptId, 403);
        }

        $request->validate([
            'manager_notes' => 'required|string|max:500',
        ], [
            'manager_notes.required' => 'سبب الرفض مطلوب',
        ]);

        if ($permissionRequest->status !== 'pending') {
            return back()->with('error', 'تمت معالجة هذا الطلب مسبقاً');
        }

        $permissionRequest->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'manager_notes' => $request->manager_notes,
        ]);
        $this->notificationService->create(
            $permissionRequest->employee->user_id,
            'permission',
            'تم رفض الإذن',
            'تم رفض طلب الإذن الخاص بك'
        );

        $this->auditService->log(
            action: 'reject_permission',
            targetType: 'permission',
            targetId: $permissionRequest->id,
            targetName: $permissionRequest->employee->user->name ?? null,
            description: 'تم رفض إذن',
            meta: [
                'employee_id' => $permissionRequest->employee_id,
                'date' => $permissionRequest->date?->format('Y-m-d') ?? null,
                'from' => $permissionRequest->from_time ?? null,
                'to' => $permissionRequest->to_time ?? null,
            ]
        );

        return redirect()->route('admin.permission-requests.index')
            ->with('success', 'تم رفض الإذن');
    }
}
