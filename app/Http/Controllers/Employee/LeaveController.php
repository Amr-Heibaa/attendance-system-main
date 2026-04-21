<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\StoreLeaveRequest;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $employee = $request->user()->employee;
        $requests = LeaveRequest::with('leaveType')
            ->where('employee_id', $employee->id)
            ->latest()
            ->paginate(15);

        return view('employee.leaves.index', compact('requests'));
    }

    public function create()
    {
        $leaveTypes = LeaveType::where('is_active', true)->get();
        return view('employee.leaves.create', compact('leaveTypes'));
    }

    public function store(StoreLeaveRequest $request)
    {
        $employee = $request->user()->employee;
        $daysCount = Carbon::parse($request->start_date)->diffInDays($request->end_date) + 1;

        LeaveRequest::create([
            'employee_id'   => $employee->id,
            'leave_type_id' => $request->leave_type_id,
            'start_date'    => $request->start_date,
            'end_date'      => $request->end_date,
            'days_count'    => $daysCount,
            'reason'        => $request->reason,
            'status'        => 'pending',
        ]);

        return redirect()->route('employee.leaves.index')
            ->with('success', 'تم تقديم طلب الإجازة بنجاح وهو قيد المراجعة');
    }
}