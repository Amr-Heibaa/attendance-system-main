<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\StoreShiftChangeRequest;
use App\Models\Shift;
use App\Models\ShiftChangeRequest;
use Illuminate\Http\Request;

class ShiftChangeController extends Controller
{
    public function index(Request $request)
    {
        $employee = $request->user()->employee;

        $requests = ShiftChangeRequest::with(['currentShift', 'requestedShift'])
            ->where('employee_id', $employee->id)
            ->latest()
            ->paginate(15);

        return view('employee.shift-changes.index', compact('requests'));
    }

    public function create(Request $request)
    {
        $employee = $request->user()->employee;
        $shifts = Shift::where('is_active', true)->get();

        return view('employee.shift-changes.create', compact('employee', 'shifts'));
    }

    public function store(StoreShiftChangeRequest $request)
    {
        $employee = $request->user()->employee;

        ShiftChangeRequest::create([
            'employee_id' => $employee->id,
            'current_shift_id' => $employee->shift_id,
            'requested_shift_id' => $request->requested_shift_id,
            'effective_date' => $request->effective_date,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->route('employee.shift-changes.index')
            ->with('success', 'تم تقديم طلب تغيير الوردية بنجاح');
    }
}