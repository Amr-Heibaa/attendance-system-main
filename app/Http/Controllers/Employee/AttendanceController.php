<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $employee = $request->user()->employee;

        $query = AttendanceRecord::where('employee_id', $employee->id)
            ->orderBy('date', 'desc');

        if ($request->filled('from')) {
            $query->whereDate('date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('date', '<=', $request->to);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $records = $query->paginate(20);

        return view('employee.attendance.index', compact('records'));
    }
}