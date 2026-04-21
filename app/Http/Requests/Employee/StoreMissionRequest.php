<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class StoreMissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'type' => 'required|in:internal,external',
            'date' => 'required|date|after_or_equal:today',
            'from_time' => 'required|date_format:H:i',
            'to_time' => 'required|date_format:H:i|after:from_time',
            'location' => 'nullable|string|max:255',
            'reason' => 'required|string|min:5|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'عنوان المهمة مطلوب',
            'type.required' => 'نوع المهمة مطلوب',
            'date.required' => 'تاريخ المهمة مطلوب',
            'date.after_or_equal' => 'لا يمكن تقديم مهمة بتاريخ سابق',
            'from_time.required' => 'وقت البداية مطلوب',
            'to_time.required' => 'وقت النهاية مطلوب',
            'to_time.after' => 'وقت النهاية يجب أن يكون بعد وقت البداية',
            'reason.required' => 'سبب المهمة مطلوب',
            'reason.min' => 'سبب المهمة قصير جدًا',
        ];
    }
}