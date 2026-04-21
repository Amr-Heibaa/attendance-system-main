<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreShiftRequest;
use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::withCount('employees')->paginate(15);
        return view('admin.shifts.index', compact('shifts'));
    }

    public function create()
    {
        return view('admin.shifts.create');
    }

    public function store(StoreShiftRequest  $request)
    {
        $request->validate([
            'name'          => 'required|string|max:100',
            'start_time'    => 'required|date_format:H:i',
            'end_time'      => 'required|date_format:H:i',
            'grace_minutes' => 'required|integer|min:0|max:60',
        ], [
            'name.required'          => 'اسم الوردية مطلوب',
            'start_time.required'    => 'وقت البدء مطلوب',
            'end_time.required'      => 'وقت الانتهاء مطلوب',
            'grace_minutes.required' => 'دقائق السماح مطلوبة',
        ]);

        Shift::create($request->all());

        return redirect()->route('admin.shifts.index')
            ->with('success', 'تم إضافة الوردية بنجاح');
    }

    public function edit(Shift $shift)
    {
        return view('admin.shifts.edit', compact('shift'));
    }

    public function update(StoreShiftRequest  $request, Shift $shift)
    {
        $request->validate([
            'name'          => 'required|string|max:100',
            'start_time'    => 'required|date_format:H:i',
            'end_time'      => 'required|date_format:H:i',
            'grace_minutes' => 'required|integer|min:0|max:60',
        ]);

        $shift->update($request->all());

        return redirect()->route('admin.shifts.index')
            ->with('success', 'تم تحديث الوردية بنجاح');
    }

    public function destroy(Shift $shift)
    {
        $shift->delete();
        return redirect()->route('admin.shifts.index')
            ->with('success', 'تم حذف الوردية بنجاح');
    }
}