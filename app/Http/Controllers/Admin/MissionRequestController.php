<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MissionRequest;
use Illuminate\Http\Request;
use App\Services\AuditService;
use App\Services\NotificationService;

class MissionRequestController extends Controller
{
    public function __construct(
        private AuditService $auditService,
        private NotificationService $notificationService
    ) {}

    public function index(Request $request)
    {
        $query = MissionRequest::with(['employee.user', 'employee.department'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest();

        if (auth()->user()->hasRole('manager')) {
            $deptId = auth()->user()->employee?->department_id;
            $query->whereHas('employee', fn($q) => $q->where('department_id', $deptId));
        }

        $requests = $query->paginate(15);

        return view('admin.mission-requests.index', compact('requests'));
    }

    public function show(MissionRequest $missionRequest)
    {
        if (auth()->user()->hasRole('manager')) {
            $deptId = auth()->user()->employee?->department_id;
            abort_if($missionRequest->employee->department_id != $deptId, 403);
        }

        $missionRequest->load(['employee.user', 'employee.department', 'approvedBy']);

        return view('admin.mission-requests.show', compact('missionRequest'));
    }

    public function approve(Request $request, MissionRequest $missionRequest)
    {
        if (auth()->user()->hasRole('manager')) {
            $deptId = auth()->user()->employee?->department_id;
            abort_if($missionRequest->employee->department_id != $deptId, 403);
        }

        $request->validate([
            'manager_notes' => 'nullable|string|max:500',
        ]);

        if ($missionRequest->status !== 'pending') {
            return back()->with('error', 'تمت معالجة هذا الطلب مسبقاً');
        }

        $missionRequest->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'manager_notes' => $request->manager_notes,
        ]);
        $this->notificationService->create(
            $missionRequest->employee->user_id,
            'mission',
            'تم اعتماد المهمة',
            'تمت الموافقة على طلب المهمة الخاص بك'
        );

        $this->auditService->log(
            action: 'approve_mission',
            targetType: 'mission',
            targetId: $missionRequest->id,
            targetName: $missionRequest->employee->user->name ?? null,
            description: 'تم اعتماد مهمة',
            meta: [
                'employee_id' => $missionRequest->employee_id,
                'title' => $missionRequest->title,
                'type' => $missionRequest->type,
                'date' => $missionRequest->date?->format('Y-m-d') ?? null,
            ]
        );

        return redirect()->route('admin.mission-requests.index')
            ->with('success', 'تم اعتماد المهمة بنجاح');
    }

    public function reject(Request $request, MissionRequest $missionRequest)
    {
        if (auth()->user()->hasRole('manager')) {
            $deptId = auth()->user()->employee?->department_id;
            abort_if($missionRequest->employee->department_id != $deptId, 403);
        }

        $request->validate([
            'manager_notes' => 'required|string|max:500',
        ], [
            'manager_notes.required' => 'سبب الرفض مطلوب',
        ]);

        if ($missionRequest->status !== 'pending') {
            return back()->with('error', 'تمت معالجة هذا الطلب مسبقاً');
        }

        $missionRequest->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'manager_notes' => $request->manager_notes,
        ]);

        $this->notificationService->create(
            $missionRequest->employee->user_id,
            'mission',
            'تم رفض المهمة',
            'تم رفض طلب المهمة الخاص بك'
        );

        $this->auditService->log(
            action: 'reject_mission',
            targetType: 'mission',
            targetId: $missionRequest->id,
            targetName: $missionRequest->employee->user->name ?? null,
            description: 'تم رفض مهمة',
            meta: [
                'employee_id' => $missionRequest->employee_id,
                'title' => $missionRequest->title,
                'type' => $missionRequest->type,
                'date' => $missionRequest->date?->format('Y-m-d') ?? null,
            ]
        );

        return redirect()->route('admin.mission-requests.index')
            ->with('success', 'تم رفض المهمة');
    }
}
