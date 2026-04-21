<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreHolidayRequest;
use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index()
    {
        $holidays = Holiday::orderBy('date', 'desc')->paginate(15);
        return view('admin.holidays.index', compact('holidays'));
    }

    public function create()
    {
        return view('admin.holidays.create');
    }

    public function store(StoreHolidayRequest  $request)
    {
        $request->validate([
            'date'    => 'required|date|unique:holidays,date',
            'name'    => 'required|string|max:100',
            'name_ar' => 'required|string|max:100',
        ], [
            'date.required'    => 'تاريخ العطلة مطلوب',
            'date.unique'      => 'هذا التاريخ مسجل بالفعل كعطلة',
            'name_ar.required' => 'اسم العطلة بالعربية مطلوب',
        ]);

        Holiday::create($request->all());

        return redirect()->route('admin.holidays.index')
            ->with('success', 'تم إضافة العطلة بنجاح');
    }

    public function edit(Holiday $holiday)
    {
        return view('admin.holidays.edit', compact('holiday'));
    }

    public function update(StoreHolidayRequest  $request, Holiday $holiday)
    {
        $request->validate([
            'date'    => 'required|date|unique:holidays,date,' . $holiday->id,
            'name'    => 'required|string|max:100',
            'name_ar' => 'required|string|max:100',
        ]);

        $holiday->update($request->all());

        return redirect()->route('admin.holidays.index')
            ->with('success', 'تم تحديث العطلة بنجاح');
    }

    public function destroy(Holiday $holiday)
    {
        $holiday->delete();
        return redirect()->route('admin.holidays.index')
            ->with('success', 'تم حذف العطلة بنجاح');
    }
}