<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\LeaveRequest;

class LeaveBalanceController extends Controller
{
    public function index()
    {
        $deptId = auth()->user()->hasRole('manager')
            ? auth()->user()->employee?->department_id
            : null;

        $employees = Employee::with('user')
            ->when($deptId, fn($q) => $q->where('department_id', $deptId))
            ->where('status', 'active')
            ->get();

        $data = $employees->map(function ($emp) {

            $used = LeaveRequest::where('employee_id', $emp->id)
                ->where('status', 'approved')
                ->sum(\DB::raw('DATEDIFF(end_date, start_date) + 1'));

            $total = 21;

            return [
                'name' => $emp->user->name,
                'total' => $total,
                'used' => $used,
                'remaining' => max($total - $used, 0),
            ];
        });

        return view('admin.leave-balances.index', compact('data'));
    }
}