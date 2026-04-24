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
use Rap2hpoutre\FastExcel\FastExcel;

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

        $employees = $query->orderBy('employee_code')->paginate(20);
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
                'user_id'                 => $user->id,
                'employee_code'           => $request->employee_code,
                'department_id'           => $request->department_id,
                'shift_id'                => $request->shift_id,
                'job_title'               => $request->job_title,
                'phone'                   => $request->phone,
                'emergency_phone'         => $request->emergency_phone,
                'national_id'             => $request->national_id,
                'birth_date'              => $request->birth_date,
                'insurance_number'        => $request->insurance_number,
                'education_qualification' => $request->education_qualification,
                'address'                 => $request->address,
                'cost_center'             => $request->cost_center,
                'work_location'           => $request->work_location,
                'hire_date'               => $request->hire_date,
                'status'                  => $request->status,
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
                'employee_code'           => $request->employee_code,
                'department_id'           => $request->department_id,
                'shift_id'                => $request->shift_id,
                'job_title'               => $request->job_title,
                'phone'                   => $request->phone,
                'emergency_phone'         => $request->emergency_phone,
                'national_id'             => $request->national_id,
                'birth_date'              => $request->birth_date,
                'insurance_number'        => $request->insurance_number,
                'education_qualification' => $request->education_qualification,
                'address'                 => $request->address,
                'cost_center'             => $request->cost_center,
                'work_location'           => $request->work_location,
                'hire_date'               => $request->hire_date,
                'status'                  => $request->status,
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

    public function importForm()
    {
        return view('admin.employees.import');
    }

    public function downloadTemplate()
    {
        $rows = collect([
            [
                'name' => 'أحمد محمد علي',
                'email' => 'ahmed@example.com',
                'password' => 'password123',
                'employee_code' => 'EMP001',
                'department_name' => 'الموارد البشرية',
                'shift_name' => 'الوردية الصباحية',
                'job_title' => 'أخصائي موارد بشرية',
                'phone' => '01011111111',
                'emergency_phone' => '01099999991',
                'national_id' => '29801011234567',
                'birth_date' => '1998-01-01',
                'insurance_number' => 'INS1001',
                'education_qualification' => 'بكالوريوس تجارة',
                'address' => 'القاهرة - مدينة نصر',
                'cost_center' => 'CC-HR-001',
                'work_location' => 'المقر الرئيسي',
                'hire_date' => now()->toDateString(),
                'status' => 'active',
            ],
        ]);

        return (new FastExcel($rows))->download('employees-template.xlsx');
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

            $name = trim((string)($row['name'] ?? ''));
            $email = trim((string)($row['email'] ?? ''));
            $password = trim((string)($row['password'] ?? ''));
            $employeeCode = trim((string)($row['employee_code'] ?? ''));
            $departmentName = trim((string)($row['department_name'] ?? ''));
            $shiftName = trim((string)($row['shift_name'] ?? ''));
            $jobTitle = trim((string)($row['job_title'] ?? ''));
            $phone = trim((string)($row['phone'] ?? ''));
            $emergencyPhone = trim((string)($row['emergency_phone'] ?? ''));
            $nationalId = trim((string)($row['national_id'] ?? ''));
            $birthDate = trim((string)($row['birth_date'] ?? ''));
            $insuranceNumber = trim((string)($row['insurance_number'] ?? ''));
            $educationQualification = trim((string)($row['education_qualification'] ?? ''));
            $address = trim((string)($row['address'] ?? ''));
            $costCenter = trim((string)($row['cost_center'] ?? ''));
            $workLocation = trim((string)($row['work_location'] ?? ''));
            $hireDate = trim((string)($row['hire_date'] ?? ''));
            $status = trim((string)($row['status'] ?? 'active'));

            if ($name === '' || $email === '' || $employeeCode === '' || $hireDate === '') {
                $skipped[] = "الصف {$rowNumber}: الاسم والبريد الإلكتروني وكود الموظف وتاريخ التعيين حقول مطلوبة";
                continue;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $skipped[] = "الصف {$rowNumber}: البريد الإلكتروني غير صحيح";
                continue;
            }

            if ($nationalId !== '' && (!ctype_digit($nationalId) || strlen($nationalId) !== 14)) {
                $skipped[] = "الصف {$rowNumber}: الرقم القومي يجب أن يكون 14 رقمًا";
                continue;
            }

            if (!in_array($status, ['active', 'inactive', 'suspended'])) {
                $status = 'active';
            }

            $department = null;
            if ($departmentName !== '') {
                $department = Department::where('name_ar', $departmentName)
                    ->orWhere('name', $departmentName)
                    ->first();

                if (!$department) {
                    $skipped[] = "الصف {$rowNumber}: القسم ({$departmentName}) غير موجود";
                    continue;
                }
            }

            $shift = null;
            if ($shiftName !== '') {
                $shift = Shift::where('name', $shiftName)->first();

                if (!$shift) {
                    $skipped[] = "الصف {$rowNumber}: الوردية ({$shiftName}) غير موجودة";
                    continue;
                }
            }

            DB::transaction(function () use (
                $name,
                $email,
                $password,
                $employeeCode,
                $department,
                $shift,
                $jobTitle,
                $phone,
                $emergencyPhone,
                $nationalId,
                $birthDate,
                $insuranceNumber,
                $educationQualification,
                $address,
                $costCenter,
                $workLocation,
                $hireDate,
                $status,
                &$imported,
                &$updated
            ) {
                $employee = Employee::where('employee_code', $employeeCode)->first();

                if ($employee) {
                    $employee->user->update([
                        'name' => $name,
                        'email' => $email,
                    ]);

                    if ($password !== '') {
                        $employee->user->update([
                            'password' => Hash::make($password),
                        ]);
                    }

                    $employee->update([
                        'department_id' => $department?->id,
                        'shift_id' => $shift?->id,
                        'job_title' => $jobTitle,
                        'phone' => $phone,
                        'emergency_phone' => $emergencyPhone,
                        'national_id' => $nationalId ?: null,
                        'birth_date' => $birthDate ?: null,
                        'insurance_number' => $insuranceNumber,
                        'education_qualification' => $educationQualification,
                        'address' => $address,
                        'cost_center' => $costCenter,
                        'work_location' => $workLocation,
                        'hire_date' => $hireDate,
                        'status' => $status,
                    ]);

                    $updated++;
                } else {
                    $existingUser = User::where('email', $email)->first();

                    if ($existingUser && $existingUser->employee) {
                        return;
                    }

                    $user = $existingUser ?: User::create([
                        'name' => $name,
                        'email' => $email,
                        'password' => Hash::make($password !== '' ? $password : 'password123'),
                        'email_verified_at' => now(),
                    ]);

                    if (!$user->hasRole('employee')) {
                        $user->assignRole('employee');
                    }

                    Employee::create([
                        'user_id' => $user->id,
                        'employee_code' => $employeeCode,
                        'department_id' => $department?->id,
                        'shift_id' => $shift?->id,
                        'job_title' => $jobTitle,
                        'phone' => $phone,
                        'emergency_phone' => $emergencyPhone,
                        'national_id' => $nationalId ?: null,
                        'birth_date' => $birthDate ?: null,
                        'insurance_number' => $insuranceNumber,
                        'education_qualification' => $educationQualification,
                        'address' => $address,
                        'cost_center' => $costCenter,
                        'work_location' => $workLocation,
                        'hire_date' => $hireDate,
                        'status' => $status,
                    ]);

                    $imported++;
                }
            });
        }

        $message = "تم استيراد {$imported} موظف، وتحديث {$updated} موظف.";

        if (!empty($skipped)) {
            $message .= ' تم تخطي بعض الصفوف: ' . implode(' | ', $skipped);
        }

        return redirect()->route('admin.employees.index')
            ->with('success', $message);
    }
}
