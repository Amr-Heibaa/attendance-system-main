<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Services\FinancialPolicyService;

class AttendanceController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        $deptId = auth()->user()->hasRole('manager')
            ? auth()->user()->employee?->department_id
            : null;

        $records = AttendanceRecord::with(['employee.user'])
            ->when(
                $deptId,
                fn($q) =>
                $q->whereHas(
                    'employee',
                    fn($e) =>
                    $e->where('department_id', $deptId)
                )
            )
            ->whereDate('date', $today)
            ->latest()
            ->get();

        $financialPolicyService = app(FinancialPolicyService::class);

        $records->transform(function ($record) use ($financialPolicyService) {
            $record->financial_penalty = $financialPolicyService->getPenaltyForAttendance($record);
            return $record;
        });
        $stats = [
            'present' => $records->where('status', 'present')->count(),
            'late' => $records->where('status', 'late')->count(),
            'absent' => $records->where('status', 'absent')->count(),
            'incomplete' => $records->whereNull('check_out')->count(),
        ];

        return view('admin.attendance.index', compact('records', 'stats'));
    }
}
