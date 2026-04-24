<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDepartmentRequest;
use App\Models\Department;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;

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

    public function store(StoreDepartmentRequest $request)
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

    public function update(StoreDepartmentRequest $request, Department $department)
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

    public function importForm()
    {
        return view('admin.departments.import');
    }

    public function downloadTemplate()
    {
        $rows = collect([
            [
                'name_ar' => 'الموارد البشرية',
                'name' => 'Human Resources',
                'description' => 'قسم الموارد البشرية',
                'is_active' => 1,
            ],
            [
                'name_ar' => 'المالية',
                'name' => 'Finance',
                'description' => 'قسم الشؤون المالية',
                'is_active' => 1,
            ],
        ]);

        return (new FastExcel($rows))->download('departments-template.xlsx');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ], [
            'file.required' => 'يرجى اختيار ملف',
            'file.mimes' => 'الملف يجب أن يكون Excel أو CSV',
        ]);

        $rows = (new FastExcel)->import($request->file('file'));

        $imported = 0;
        $updated = 0;
        $skipped = [];

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;

            $nameAr = trim((string)($row['name_ar'] ?? ''));
            $name = trim((string)($row['name'] ?? ''));
            $description = trim((string)($row['description'] ?? ''));
            $isActiveRaw = $row['is_active'] ?? 1;

            if ($nameAr === '' || $name === '') {
                $skipped[] = "الصف {$rowNumber}: اسم القسم العربي والإنجليزي مطلوبان";
                continue;
            }

            $isActive = in_array((string)$isActiveRaw, ['1', 'true', 'TRUE', 'yes', 'Yes', 'نعم'], true);

            $department = Department::where('name_ar', $nameAr)
                ->orWhere('name', $name)
                ->first();

            if ($department) {
                $department->update([
                    'name_ar' => $nameAr,
                    'name' => $name,
                    'description' => $description ?: $department->description,
                    'is_active' => $isActive,
                ]);

                $updated++;
            } else {
                Department::create([
                    'name_ar' => $nameAr,
                    'name' => $name,
                    'description' => $description,
                    'is_active' => $isActive,
                ]);

                $imported++;
            }
        }

        $message = "تم استيراد {$imported} قسم، وتحديث {$updated} قسم.";

        if (!empty($skipped)) {
            $message .= ' تم تخطي بعض الصفوف: ' . implode(' | ', $skipped);
        }

        return redirect()->route('admin.departments.index')
            ->with('success', $message);
    }
}