<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompOffRequest;
use Illuminate\Http\Request;
use App\Services\AuditService;
use App\Services\NotificationService;

class CompOffRequestController extends Controller
{
    public function __construct(
        private AuditService $auditService,
        private NotificationService $notificationService
    ) {}

    public function index(Request $request)
    {
        $query = CompOffRequest::with(['employee.user', 'employee.department'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest();

        if (auth()->user()->hasRole('manager')) {
            $deptId = auth()->user()->employee?->department_id;
            $query->whereHas('employee', fn($q) => $q->where('department_id', $deptId));
        }

        $requests = $query->paginate(15);

        return view('admin.comp-off-requests.index', compact('requests'));
    }

    public function show(CompOffRequest $compOffRequest)
    {
        if (auth()->user()->hasRole('manager')) {
            $deptId = auth()->user()->employee?->department_id;
            abort_if($compOffRequest->employee->department_id != $deptId, 403);
        }

        $compOffRequest->load(['employee.user', 'employee.department', 'approvedBy']);

        return view('admin.comp-off-requests.show', compact('compOffRequest'));
    }

    public function approve(Request $request, CompOffRequest $compOffRequest)
    {
        if (auth()->user()->hasRole('manager')) {
            $deptId = auth()->user()->employee?->department_id;
            abort_if($compOffRequest->employee->department_id != $deptId, 403);
        }

        $request->validate([
            'manager_notes' => 'nullable|string|max:500',
        ]);

        if ($compOffRequest->status !== 'pending') {
            return back()->with('error', 'تمت معالجة هذا الطلب مسبقاً');
        }

        $compOffRequest->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'manager_notes' => $request->manager_notes,
        ]);
        $this->notificationService->create(
            $compOffRequest->employee->user_id,
            'comp_off',
            'تم اعتماد بدل الراحة',
            'تمت الموافقة على طلب بدل الراحة الخاص بك'
        );

        $this->auditService->log(
            action: 'approve_comp_off',
            targetType: 'comp_off',
            targetId: $compOffRequest->id,
            targetName: $compOffRequest->employee->user->name ?? null,
            description: 'تم اعتماد طلب بدل راحة',
            meta: [
                'employee_id' => $compOffRequest->employee_id,
                'worked_on_date' => $compOffRequest->worked_on_date?->format('Y-m-d') ?? null,
                'requested_off_date' => $compOffRequest->requested_off_date?->format('Y-m-d') ?? null,
            ]
        );

        return redirect()->route('admin.comp-off-requests.index')
            ->with('success', 'تم اعتماد طلب بدل الراحة');
    }

    public function reject(Request $request, CompOffRequest $compOffRequest)
    {
        if (auth()->user()->hasRole('manager')) {
            $deptId = auth()->user()->employee?->department_id;
            abort_if($compOffRequest->employee->department_id != $deptId, 403);
        }

        $request->validate([
            'manager_notes' => 'required|string|max:500',
        ], [
            'manager_notes.required' => 'سبب الرفض مطلوب',
        ]);

        if ($compOffRequest->status !== 'pending') {
            return back()->with('error', 'تمت معالجة هذا الطلب مسبقاً');
        }

        $compOffRequest->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'manager_notes' => $request->manager_notes,
        ]);
        $this->notificationService->create(
            $compOffRequest->employee->user_id,
            'comp_off',
            'تم رفض بدل الراحة',
            'تم رفض طلب بدل الراحة الخاص بك'
        );

        $this->auditService->log(
            action: 'reject_comp_off',
            targetType: 'comp_off',
            targetId: $compOffRequest->id,
            targetName: $compOffRequest->employee->user->name ?? null,
            description: 'تم رفض طلب بدل راحة',
            meta: [
                'employee_id' => $compOffRequest->employee_id,
                'worked_on_date' => $compOffRequest->worked_on_date?->format('Y-m-d') ?? null,
                'requested_off_date' => $compOffRequest->requested_off_date?->format('Y-m-d') ?? null,
            ]
        );

        return redirect()->route('admin.comp-off-requests.index')
            ->with('success', 'تم رفض طلب بدل الراحة');
    }
}
