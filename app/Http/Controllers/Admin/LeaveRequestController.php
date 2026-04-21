<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use App\Services\AuditService;
use App\Services\NotificationService;

class LeaveRequestController extends Controller
{
    public function __construct(
        private AuditService $auditService,
        private NotificationService $notificationService
    ) {}
    public function index(Request $request)
    {
        $query = LeaveRequest::with(['employee.user', 'leaveType'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest();

        $requests = $query->paginate(15);

        return view('admin.leave-requests.index', compact('requests'));
    }

    public function show(LeaveRequest $leaveRequest)
    {
        $leaveRequest->load(['employee.user', 'leaveType', 'approvedBy']);
        return view('admin.leave-requests.show', compact('leaveRequest'));
    }

    public function approve(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate([
            'manager_notes' => 'nullable|string|max:500',
        ]);

        if ($leaveRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'تمت معالجة هذا الطلب مسبقاً');
        }

        $leaveRequest->update([
            'status'        => 'approved',
            'approved_by'   => auth()->id(),
            'approved_at'   => now(),
            'manager_notes' => $request->manager_notes,
        ]);
        $this->notificationService->create(
            $leaveRequest->employee->user_id,
            'leave',
            'تم اعتماد طلب الإجازة',
            'تمت الموافقة على طلب الإجازة الخاص بك'
        );

        $this->auditService->log(
            action: 'approve_leave_request',
            targetType: 'leave_request',
            targetId: $leaveRequest->id,
            targetName: $leaveRequest->employee->user->name ?? null,
            description: 'تم اعتماد طلب إجازة',
            meta: [
                'employee_id' => $leaveRequest->employee_id,
                'leave_type_id' => $leaveRequest->leave_type_id,
                'start_date' => $leaveRequest->start_date->format('Y-m-d'),
                'end_date' => $leaveRequest->end_date->format('Y-m-d'),
            ]
        );

        // تسجيل أيام الإجازة في سجلات الحضور
        $current = $leaveRequest->start_date->copy();
        while ($current->lte($leaveRequest->end_date)) {
            AttendanceRecord::updateOrCreate(
                ['employee_id' => $leaveRequest->employee_id, 'date' => $current->toDateString()],
                ['status' => 'leave', 'check_in' => null, 'check_out' => null]
            );
            $current->addDay();
        }

        return redirect()->route('admin.leave-requests.index')
            ->with('success', 'تم قبول طلب الإجازة بنجاح');
    }

    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate([
            'manager_notes' => 'required|string|max:500',
        ], [
            'manager_notes.required' => 'يرجى كتابة سبب الرفض',
        ]);

        if ($leaveRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'تمت معالجة هذا الطلب مسبقاً');
        }

        $leaveRequest->update([
            'status'        => 'rejected',
            'approved_by'   => auth()->id(),
            'approved_at'   => now(),
            'manager_notes' => $request->manager_notes,
        ]);
        $this->notificationService->create(
            $leaveRequest->employee->user_id,
            'leave',
            'تم رفض طلب الإجازة',
            'تم رفض طلب الإجازة الخاص بك'
        );

        $this->auditService->log(
            action: 'reject_leave_request',
            targetType: 'leave_request',
            targetId: $leaveRequest->id,
            targetName: $leaveRequest->employee->user->name ?? null,
            description: 'تم رفض طلب إجازة',
            meta: [
                'employee_id' => $leaveRequest->employee_id,
                'leave_type_id' => $leaveRequest->leave_type_id,
                'start_date' => $leaveRequest->start_date->format('Y-m-d'),
                'end_date' => $leaveRequest->end_date->format('Y-m-d'),
            ]
        );

        return redirect()->route('admin.leave-requests.index')
            ->with('success', 'تم رفض طلب الإجازة');
    }
}
