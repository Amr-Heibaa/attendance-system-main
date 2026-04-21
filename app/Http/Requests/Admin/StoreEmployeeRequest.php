<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $employeeId = $this->route('employee')?->id;
        $userId = $this->route('employee')?->user_id;

        return [
            'name'                     => 'required|string|max:255',
            'email'                    => 'required|email|unique:users,email,' . $userId,
            'password'                 => $this->isMethod('POST') ? 'required|min:8' : 'nullable|min:8',

            'employee_code'            => 'required|string|max:50|unique:employees,employee_code,' . $employeeId,
            'department_id'            => 'nullable|exists:departments,id',
            'shift_id'                 => 'nullable|exists:shifts,id',
            'job_title'                => 'nullable|string|max:100',

            'phone'                    => 'nullable|string|max:20',
            'emergency_phone'          => 'nullable|string|max:20',
            'national_id' => [
                'nullable',
                'digits:14', // لازم 14 رقم بالظبط
                'regex:/^[0-9]+$/', // أرقام فقط
                'unique:employees,national_id,' . $employeeId,
            ],
            'birth_date'               => 'nullable|date|before:today',
            'insurance_number'         => 'nullable|string|max:50',
            'education_qualification'  => 'nullable|string|max:255',
            'address'                  => 'nullable|string|max:1000',
            'cost_center'              => 'nullable|string|max:100',
            'work_location'            => 'nullable|string|max:255',

            'hire_date'                => 'required|date',
            'status'                   => 'required|in:active,inactive,suspended',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'                    => 'اسم الموظف مطلوب',
            'email.required'                   => 'البريد الإلكتروني مطلوب',
            'email.email'                      => 'صيغة البريد الإلكتروني غير صحيحة',
            'email.unique'                     => 'البريد الإلكتروني مستخدم بالفعل',

            'password.required'                => 'كلمة المرور مطلوبة',
            'password.min'                     => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',

            'employee_code.required'           => 'رقم الموظف مطلوب',
            'employee_code.unique'             => 'رقم الموظف مستخدم بالفعل',

            'national_id.digits' => 'الرقم القومي يجب أن يكون 14 رقم',
            'national_id.regex' => 'الرقم القومي يجب أن يحتوي على أرقام فقط',
            'national_id.unique' => 'الرقم القومي مستخدم بالفعل',
            'birth_date.before'                => 'تاريخ الميلاد يجب أن يكون قبل اليوم',
            'hire_date.required'               => 'تاريخ التعيين مطلوب',

            'status.required'                  => 'حالة الموظف مطلوبة',
        ];
    }
}
