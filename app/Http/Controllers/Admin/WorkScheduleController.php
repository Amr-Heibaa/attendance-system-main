<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkSchedule;
use Illuminate\Http\Request;

class WorkScheduleController extends Controller
{
    public function index()
    {
        $schedules = WorkSchedule::latest()->paginate(15);
        return view('admin.work-schedules.index', compact('schedules'));
    }

    public function create()
    {
        return view('admin.work-schedules.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required',
            'grace_minutes' => 'required|integer|min:0',
            'work_days' => 'nullable|array',
            'effective_from' => 'required|date',
            'effective_to' => 'nullable|date|after_or_equal:effective_from',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'approved_by' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        if ($request->boolean('is_default')) {
            WorkSchedule::query()->update(['is_default' => false]);
        }

        $data['is_default'] = $request->boolean('is_default');
        $data['is_active'] = $request->boolean('is_active');
        $data['work_days'] = $request->work_days ?? [];

        WorkSchedule::create($data);

        return redirect()->route('admin.work-schedules.index')
            ->with('success', 'تم إضافة الموعد المنتظم بنجاح');
    }

    public function edit(WorkSchedule $workSchedule)
    {
        return view('admin.work-schedules.edit', compact('workSchedule'));
    }

    public function update(Request $request, WorkSchedule $workSchedule)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required',
            'grace_minutes' => 'required|integer|min:0',
            'work_days' => 'nullable|array',
            'effective_from' => 'required|date',
            'effective_to' => 'nullable|date|after_or_equal:effective_from',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'approved_by' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        if ($request->boolean('is_default')) {
            WorkSchedule::where('id', '!=', $workSchedule->id)->update(['is_default' => false]);
        }

        $data['is_default'] = $request->boolean('is_default');
        $data['is_active'] = $request->boolean('is_active');
        $data['work_days'] = $request->work_days ?? [];

        $workSchedule->update($data);

        return redirect()->route('admin.work-schedules.index')
            ->with('success', 'تم تحديث الموعد المنتظم بنجاح');
    }

    public function destroy(WorkSchedule $workSchedule)
    {
        $workSchedule->delete();

        return redirect()->route('admin.work-schedules.index')
            ->with('success', 'تم حذف الموعد المنتظم بنجاح');
    }
}