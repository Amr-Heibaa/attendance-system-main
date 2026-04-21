<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\StoreMissionRequest;
use App\Models\MissionRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MissionController extends Controller
{
    public function index(Request $request)
    {
        $employee = $request->user()->employee;

        $requests = MissionRequest::where('employee_id', $employee->id)
            ->latest()
            ->paginate(15);

        return view('employee.missions.index', compact('requests'));
    }

    public function create()
    {
        return view('employee.missions.create');
    }

    public function store(StoreMissionRequest $request)
    {
        $employee = $request->user()->employee;

        $from = Carbon::createFromFormat('H:i', $request->from_time);
        $to   = Carbon::createFromFormat('H:i', $request->to_time);

        MissionRequest::create([
            'employee_id'   => $employee->id,
            'title'         => $request->title,
            'type'          => $request->type,
            'date'          => $request->date,
            'from_time'     => $request->from_time,
            'to_time'       => $request->to_time,
            'minutes_count' => $from->diffInMinutes($to),
            'location'      => $request->location,
            'reason'        => $request->reason,
            'status'        => 'pending',
        ]);

        return redirect()->route('employee.missions.index')
            ->with('success', 'تم تقديم طلب المهمة بنجاح');
    }
}