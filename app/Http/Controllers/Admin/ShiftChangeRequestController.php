<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShiftChangeRequest;
use Illuminate\Http\Request;
use App\Services\AuditService;
use App\Services\NotificationService;

class ShiftChangeRequestController extends Controller
{
    public function __construct(
        private AuditService $auditService,
        private NotificationService $notificationService
    ) {}

    public function index(Request $request)
    {
        $query = ShiftChangeRequest::with([
            'employee.user',
            'employee.department',
            'currentShift',
            'requestedShift'
        ])->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest();

        if (auth()->user()->hasRole('manager')) {
            $deptId = auth()->user()->employee?->department_id;
            $query->whereHas('employee', fn($q) => $q->where('department_id', $deptId));
        }

        $requests = $query->paginate(15);

        return view('admin.shift-change-requests.index', compact('requests'));
    }

    public function show(ShiftChangeRequest $shiftChangeRequest)
    {
        if (auth()->user()->hasRole('manager')) {
            $deptId = auth()->user()->employee?->department_id;
            abort_if($shiftChangeRequest->employee->department_id != $deptId, 403);
        }

        $shiftChangeRequest->load([
            'employee.user',
            'employee.department',
            'currentShift',
            'requestedShift',
            'approvedBy'
        ]);

        return view('admin.shift-change-requests.show', compact('shiftChangeRequest'));
    }

    public function approve(Request $request, ShiftChangeRequest $shiftChangeRequest)
    {
        if (auth()->user()->hasRole('manager')) {
            $deptId = auth()->user()->employee?->department_id;
            abort_if($shiftChangeRequest->employee->department_id != $deptId, 403);
        }

        $request->validate([
            'manager_notes' => 'nullable|string|max:500',
        ]);

        if ($shiftChangeRequest->status !== 'pending') {
            return back()->with('error', 'تمت معالجة هذا الطلب مسبقاً');
        }

        $shiftChangeRequest->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'manager_notes' => $request->manager_notes,
        ]);

        $this->notificationService->create(
            $shiftChangeRequest->employee->user_id,
            'shift_change',
            'تم اعتماد تغيير الوردية',
            'تمت الموافقة على طلب تغيير الوردية الخاص بك'
        );

        // تطبيق تغيير الوردية مباشرة
        $shiftChangeRequest->employee->update([
            'shift_id' => $shiftChangeRequest->requested_shift_id,
        ]);

        $this->auditService->log(
            action: 'approve_shift_change',
            targetType: 'shift_change',
            targetId: $shiftChangeRequest->id,
            targetName: $shiftChangeRequest->employee->user->name ?? null,
            description: 'تم اعتماد طلب تغيير وردية',
            meta: [
                'employee_id' => $shiftChangeRequest->employee_id,
                'current_shift' => $shiftChangeRequest->currentShift?->name,
                'requested_shift' => $shiftChangeRequest->requestedShift?->name,
                'effective_date' => $shiftChangeRequest->effective_date?->format('Y-m-d') ?? null,
            ]
        );

        return redirect()->route('admin.shift-change-requests.index')
            ->with('success', 'تم اعتماد طلب تغيير الوردية وتحديث وردية الموظف');
    }

    public function reject(Request $request, ShiftChangeRequest $shiftChangeRequest)
    {
        if (auth()->user()->hasRole('manager')) {
            $deptId = auth()->user()->employee?->department_id;
            abort_if($shiftChangeRequest->employee->department_id != $deptId, 403);
        }

        $request->validate([
            'manager_notes' => 'required|string|max:500',
        ], [
            'manager_notes.required' => 'سبب الرفض مطلوب',
        ]);

        if ($shiftChangeRequest->status !== 'pending') {
            return back()->with('error', 'تمت معالجة هذا الطلب مسبقاً');
        }

        $shiftChangeRequest->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'manager_notes' => $request->manager_notes,
        ]);
        $this->notificationService->create(
            $shiftChangeRequest->employee->user_id,
            'shift_change',
            'تم رفض تغيير الوردية',
            'تم رفض طلب تغيير الوردية الخاص بك'
        );

        $this->auditService->log(
            action: 'reject_shift_change',
            targetType: 'shift_change',
            targetId: $shiftChangeRequest->id,
            targetName: $shiftChangeRequest->employee->user->name ?? null,
            description: 'تم رفض طلب تغيير وردية',
            meta: [
                'employee_id' => $shiftChangeRequest->employee_id,
                'current_shift' => $shiftChangeRequest->currentShift?->name,
                'requested_shift' => $shiftChangeRequest->requestedShift?->name,
                'effective_date' => $shiftChangeRequest->effective_date?->format('Y-m-d') ?? null,
            ]
        );

        return redirect()->route('admin.shift-change-requests.index')
            ->with('success', 'تم رفض طلب تغيير الوردية');
    }
}
