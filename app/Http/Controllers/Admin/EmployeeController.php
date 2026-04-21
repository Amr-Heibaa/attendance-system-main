<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEmployeeRequest;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with(['user', 'department', 'shift'])
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($subQ) use ($request) {
                    $subQ->whereHas('user', fn($u) => $u->where('name', 'like', "%{$request->search}%"))
                        ->orWhere('employee_code', 'like', "%{$request->search}%")
                        ->orWhere('national_id', 'like', "%{$request->search}%")
                        ->orWhere('phone', 'like', "%{$request->search}%");
                });
            })
            ->when($request->department_id, fn($q) => $q->where('department_id', $request->department_id))
            ->when($request->status, fn($q) => $q->where('status', $request->status));

        $employees = $query->paginate(15);
        $departments = Department::all();

        return view('admin.employees.index', compact('employees', 'departments'));
    }

    public function create()
    {
        $departments = Department::where('is_active', true)->get();
        $shifts = Shift::where('is_active', true)->get();

        return view('admin.employees.create', compact('departments', 'shifts'));
    }

    public function store(StoreEmployeeRequest $request)
    {
        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'              => $request->name,
                'email'             => $request->email,
                'password'          => Hash::make($request->password),
                'email_verified_at' => now(),
            ]);

            $user->assignRole('employee');

            Employee::create([
                'user_id'                    => $user->id,
                'employee_code'              => $request->employee_code,
                'department_id'              => $request->department_id,
                'shift_id'                   => $request->shift_id,
                'job_title'                  => $request->job_title,
                'phone'                      => $request->phone,
                'emergency_phone'            => $request->emergency_phone,
                'national_id'                => $request->national_id,
                'birth_date'                 => $request->birth_date,
                'insurance_number'           => $request->insurance_number,
                'education_qualification'    => $request->education_qualification,
                'address'                    => $request->address,
                'cost_center'                => $request->cost_center,
                'work_location'              => $request->work_location,
                'hire_date'                  => $request->hire_date,
                'status'                     => $request->status,
            ]);
        });

        return redirect()->route('admin.employees.index')
            ->with('success', 'تم إضافة الموظف بنجاح');
    }

    public function edit(Employee $employee)
    {
        $departments = Department::where('is_active', true)->get();
        $shifts = Shift::where('is_active', true)->get();

        return view('admin.employees.edit', compact('employee', 'departments', 'shifts'));
    }

    public function update(StoreEmployeeRequest $request, Employee $employee)
    {
        DB::transaction(function () use ($request, $employee) {
            $employee->user->update([
                'name'  => $request->name,
                'email' => $request->email,
            ]);

            if ($request->filled('password')) {
                $employee->user->update([
                    'password' => Hash::make($request->password),
                ]);
            }

            $employee->update([
                'employee_code'              => $request->employee_code,
                'department_id'              => $request->department_id,
                'shift_id'                   => $request->shift_id,
                'job_title'                  => $request->job_title,
                'phone'                      => $request->phone,
                'emergency_phone'            => $request->emergency_phone,
                'national_id'                => $request->national_id,
                'birth_date'                 => $request->birth_date,
                'insurance_number'           => $request->insurance_number,
                'education_qualification'    => $request->education_qualification,
                'address'                    => $request->address,
                'cost_center'                => $request->cost_center,
                'work_location'              => $request->work_location,
                'hire_date'                  => $request->hire_date,
                'status'                     => $request->status,
            ]);

            if ($request->filled('role')) {
                $employee->user->syncRoles([$request->role]);
            }
        });

        return redirect()->route('admin.employees.index')
            ->with('success', 'تم تحديث بيانات الموظف بنجاح');
    }

    public function destroy(Employee $employee)
    {
        $employee->user->delete();

        return redirect()->route('admin.employees.index')
            ->with('success', 'تم حذف الموظف بنجاح');
    }
}
