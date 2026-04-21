<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class StorePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => 'required|date|after_or_equal:today',
            'type' => 'required|in:late_arrival,early_leave,temporary_exit',
            'from_time' => 'required|date_format:H:i',
            'to_time' => 'required|date_format:H:i|after:from_time',
            'reason' => 'required|string|min:5|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'date.required' => 'تاريخ الإذن مطلوب',
            'date.after_or_equal' => 'لا يمكن تقديم إذن بتاريخ سابق',
            'type.required' => 'نوع الإذن مطلوب',
            'from_time.required' => 'وقت البداية مطلوب',
            'to_time.required' => 'وقت النهاية مطلوب',
            'to_time.after' => 'وقت النهاية يجب أن يكون بعد وقت البداية',
            'reason.required' => 'سبب الإذن مطلوب',
            'reason.min' => 'سبب الإذن قصير جدًا',
        ];
    }
}