<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date'    => 'required|date|after_or_equal:today',
            'end_date'      => 'required|date|after_or_equal:start_date',
            'reason'        => 'required|string|min:10|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'leave_type_id.required' => 'يرجى اختيار نوع الإجازة',
            'leave_type_id.exists'   => 'نوع الإجازة غير صالح',
            'start_date.required'    => 'يرجى تحديد تاريخ بدء الإجازة',
            'start_date.after_or_equal' => 'لا يمكن طلب إجازة في تاريخ ماضٍ',
            'end_date.required'      => 'يرجى تحديد تاريخ انتهاء الإجازة',
            'end_date.after_or_equal' => 'تاريخ الانتهاء يجب أن يكون بعد تاريخ البدء أو مساوياً له',
            'reason.required'        => 'يرجى كتابة سبب الإجازة',
            'reason.min'             => 'سبب الإجازة يجب أن لا يقل عن 10 أحرف',
        ];
    }
}