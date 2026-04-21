<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompOffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'worked_on_date' => 'required|date',
            'requested_off_date' => 'required|date|after_or_equal:today',
            'reason' => 'required|string|min:5|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'worked_on_date.required' => 'تاريخ العمل المطلوب تعويضه مطلوب',
            'requested_off_date.required' => 'تاريخ بدل الراحة مطلوب',
            'requested_off_date.after_or_equal' => 'تاريخ بدل الراحة يجب أن يكون اليوم أو بعده',
            'reason.required' => 'السبب مطلوب',
            'reason.min' => 'السبب قصير جدًا',
        ];
    }
}