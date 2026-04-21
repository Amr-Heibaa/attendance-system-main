@php
    $days = [
        'Sunday' => 'الأحد',
        'Monday' => 'الاثنين',
        'Tuesday' => 'الثلاثاء',
        'Wednesday' => 'الأربعاء',
        'Thursday' => 'الخميس',
        'Friday' => 'الجمعة',
        'Saturday' => 'السبت',
    ];

    $selectedDays = old('work_days', $workSchedule->work_days ?? []);
@endphp

<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">اسم الموعد *</label>
            <input type="text" name="name" value="{{ old('name', $workSchedule->name ?? '') }}"
                   class="w-full border border-gray-300 rounded-xl px-4 py-2.5">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">دقائق السماح *</label>
            <input type="number" name="grace_minutes" value="{{ old('grace_minutes', $workSchedule->grace_minutes ?? 15) }}"
                   class="w-full border border-gray-300 rounded-xl px-4 py-2.5">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">وقت البداية *</label>
            <input type="time" name="start_time" value="{{ old('start_time', $workSchedule->start_time ?? '') }}"
                   class="w-full border border-gray-300 rounded-xl px-4 py-2.5">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">وقت النهاية *</label>
            <input type="time" name="end_time" value="{{ old('end_time', $workSchedule->end_time ?? '') }}"
                   class="w-full border border-gray-300 rounded-xl px-4 py-2.5">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">من تاريخ *</label>
            <input type="date" name="effective_from" value="{{ old('effective_from', isset($workSchedule) && $workSchedule->effective_from ? $workSchedule->effective_from->format('Y-m-d') : '') }}"
                   class="w-full border border-gray-300 rounded-xl px-4 py-2.5">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">إلى تاريخ</label>
            <input type="date" name="effective_to" value="{{ old('effective_to', isset($workSchedule) && $workSchedule->effective_to ? $workSchedule->effective_to->format('Y-m-d') : '') }}"
                   class="w-full border border-gray-300 rounded-xl px-4 py-2.5">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">معتمد بواسطة</label>
            <input type="text" name="approved_by" value="{{ old('approved_by', $workSchedule->approved_by ?? '') }}"
                   class="w-full border border-gray-300 rounded-xl px-4 py-2.5">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">أيام العمل</label>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @foreach($days as $value => $label)
                <label class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-3 py-2">
                    <input type="checkbox" name="work_days[]" value="{{ $value }}"
                        {{ in_array($value, $selectedDays ?? []) ? 'checked' : '' }}>
                    <span class="text-sm">{{ $label }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">ملاحظات</label>
        <textarea name="notes" rows="3"
                  class="w-full border border-gray-300 rounded-xl px-4 py-2.5">{{ old('notes', $workSchedule->notes ?? '') }}</textarea>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <label class="flex items-center gap-2">
            <input type="checkbox" name="is_default" value="1"
                {{ old('is_default', $workSchedule->is_default ?? false) ? 'checked' : '' }}>
            <span class="text-sm">تعيين كموعد افتراضي</span>
        </label>

        <label class="flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1"
                {{ old('is_active', isset($workSchedule) ? $workSchedule->is_active : true) ? 'checked' : '' }}>
            <span class="text-sm">نشط</span>
        </label>
    </div>
</div>