<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    public function index()
    {
        $leaveTypes = LeaveType::paginate(15);
        return view('admin.leave-types.index', compact('leaveTypes'));
    }

    public function create()
    {
        return view('admin.leave-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'               => 'required|string|max:100',
            'name_ar'            => 'required|string|max:100',
            'max_days_per_year'  => 'required|integer|min:0',
        ], [
            'name_ar.required'   => 'اسم نوع الإجازة بالعربية مطلوب',
            'max_days_per_year.required' => 'الحد الأقصى للأيام مطلوب',
        ]);

        LeaveType::create($request->all());

        return redirect()->route('admin.leave-types.index')
            ->with('success', 'تم إضافة نوع الإجازة بنجاح');
    }

    public function edit(LeaveType $leaveType)
    {
        return view('admin.leave-types.edit', compact('leaveType'));
    }

    public function update(Request $request, LeaveType $leaveType)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'name_ar' => 'required|string|max:100',
            'max_days_per_year' => 'required|integer|min:0',
        ]);

        $leaveType->update($request->all());

        return redirect()->route('admin.leave-types.index')
            ->with('success', 'تم تحديث نوع الإجازة بنجاح');
    }

    public function destroy(LeaveType $leaveType)
    {
        $leaveType->delete();
        return redirect()->route('admin.leave-types.index')
            ->with('success', 'تم حذف نوع الإجازة بنجاح');
    }
}