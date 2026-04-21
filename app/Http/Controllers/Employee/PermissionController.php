<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\StorePermissionRequest;
use App\Models\PermissionRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $employee = $request->user()->employee;

        $requests = PermissionRequest::where('employee_id', $employee->id)
            ->latest()
            ->paginate(15);

        return view('employee.permissions.index', compact('requests'));
    }

    public function create()
    {
        return view('employee.permissions.create');
    }

    public function store(StorePermissionRequest $request)
    {
        $employee = $request->user()->employee;

        $from = Carbon::createFromFormat('H:i', $request->from_time);
        $to   = Carbon::createFromFormat('H:i', $request->to_time);

        PermissionRequest::create([
            'employee_id'   => $employee->id,
            'date'          => $request->date,
            'type'          => $request->type,
            'from_time'     => $request->from_time,
            'to_time'       => $request->to_time,
            'minutes_count' => $from->diffInMinutes($to),
            'reason'        => $request->reason,
            'status'        => 'pending',
        ]);

        return redirect()->route('employee.permissions.index')
            ->with('success', 'تم تقديم طلب الإذن بنجاح');
    }
}