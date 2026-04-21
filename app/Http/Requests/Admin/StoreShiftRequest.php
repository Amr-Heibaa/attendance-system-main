<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreShiftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $shiftId = $this->route('shift')?->id;

        return [
            'name'          => 'required|string|max:100|unique:shifts,name,' . $shiftId,
            'start_time'    => 'required|date_format:H:i',
            'end_time'      => 'required|date_format:H:i',
            'grace_minutes' => 'required|integer|min:0|max:180',
            'is_active'     => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'       => 'اسم الوردية مطلوب',
            'name.unique'         => 'اسم الوردية مستخدم بالفعل',
            'start_time.required' => 'وقت البدء مطلوب',
            'end_time.required'   => 'وقت الانتهاء مطلوب',
        ];
    }
}