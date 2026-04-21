<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDepartmentRequest;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::withCount('employees')->paginate(15);
        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        return view('admin.departments.create');
    }

    public function store(StoreDepartmentRequest  $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'name_ar' => 'required|string|max:100',
        ], [
            'name.required'    => 'اسم القسم (إنجليزي) مطلوب',
            'name_ar.required' => 'اسم القسم (عربي) مطلوب',
        ]);

        Department::create($request->only('name', 'name_ar', 'description'));

        return redirect()->route('admin.departments.index')
            ->with('success', 'تم إضافة القسم بنجاح');
    }

    public function edit(Department $department)
    {
        return view('admin.departments.edit', compact('department'));
    }

    public function update(StoreDepartmentRequest  $request, Department $department)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'name_ar' => 'required|string|max:100',
        ]);

        $department->update($request->only('name', 'name_ar', 'description', 'is_active'));

        return redirect()->route('admin.departments.index')
            ->with('success', 'تم تحديث القسم بنجاح');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('admin.departments.index')
            ->with('success', 'تم حذف القسم بنجاح');
    }
}