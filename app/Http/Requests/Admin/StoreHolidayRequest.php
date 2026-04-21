<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreHolidayRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $holidayId = $this->route('holiday')?->id;

        return [
            'date'         => 'required|date|unique:holidays,date,' . $holidayId,
            'name'         => 'required|string|max:100',
            'name_ar'      => 'required|string|max:100',
            'is_recurring' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'date.required'    => 'تاريخ العطلة مطلوب',
            'date.unique'      => 'هذا التاريخ مسجل بالفعل كعطلة',
            'name.required'    => 'اسم العطلة (إنجليزي) مطلوب',
            'name_ar.required' => 'اسم العطلة (عربي) مطلوب',
        ];
    }
}