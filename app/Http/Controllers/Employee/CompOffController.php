<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\StoreCompOffRequest;
use App\Models\CompOffRequest;
use Illuminate\Http\Request;

class CompOffController extends Controller
{
    public function index(Request $request)
    {
        $employee = $request->user()->employee;

        $requests = CompOffRequest::where('employee_id', $employee->id)
            ->latest()
            ->paginate(15);

        return view('employee.comp-off.index', compact('requests'));
    }

    public function create()
    {
        return view('employee.comp-off.create');
    }

    public function store(StoreCompOffRequest $request)
    {
        $employee = $request->user()->employee;

        CompOffRequest::create([
            'employee_id' => $employee->id,
            'worked_on_date' => $request->worked_on_date,
            'requested_off_date' => $request->requested_off_date,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->route('employee.comp-off.index')
            ->with('success', 'تم تقديم طلب بدل الراحة بنجاح');
    }
}