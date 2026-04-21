<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class CheckInRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // صلاحيات الموظف عبر middleware roles
    }

    public function rules(): array
    {
        return [
            // لو حابب مستقبلاً تمنع Check-in من خارج موقع الشركة:
            // 'lat' => 'nullable|numeric',
            // 'lng' => 'nullable|numeric',
            // 'device_id' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [];
    }
}