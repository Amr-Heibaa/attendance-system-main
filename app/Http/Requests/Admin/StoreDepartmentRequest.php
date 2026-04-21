<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // محمي بالـ middleware role أصلاً
    }

    public function rules(): array
    {
        $departmentId = $this->route('department')?->id;

        return [
            'name'        => 'required|string|max:100|unique:departments,name,' . $departmentId,
            'name_ar'     => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'is_active'   => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'اسم القسم (إنجليزي) مطلوب',
            'name.unique'      => 'اسم القسم (إنجليزي) مستخدم بالفعل',
            'name_ar.required' => 'اسم القسم (عربي) مطلوب',
        ];
    }
}