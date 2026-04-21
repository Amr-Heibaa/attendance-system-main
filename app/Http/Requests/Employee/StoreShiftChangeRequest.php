<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class StoreShiftChangeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'requested_shift_id' => 'required|exists:shifts,id',
            'effective_date' => 'required|date|after_or_equal:today',
            'reason' => 'required|string|min:5|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'requested_shift_id.required' => 'الوردية المطلوبة مطلوبة',
            'requested_shift_id.exists' => 'الوردية المختارة غير صحيحة',
            'effective_date.required' => 'تاريخ التنفيذ مطلوب',
            'effective_date.after_or_equal' => 'تاريخ التنفيذ يجب أن يكون اليوم أو بعده',
            'reason.required' => 'سبب تغيير الوردية مطلوب',
            'reason.min' => 'سبب الطلب قصير جدًا',
        ];
    }
}