<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">اسم الشريحة *</label>
            <input type="text" name="name" value="{{ old('name', $financialPolicy->name ?? '') }}"
                   class="w-full border border-gray-300 rounded-xl px-4 py-2.5">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">النوع *</label>
            <select name="type" class="w-full border border-gray-300 rounded-xl px-4 py-2.5">
                <option value="late" {{ old('type', $financialPolicy->type ?? '') === 'late' ? 'selected' : '' }}>تأخير</option>
                <option value="absence" {{ old('type', $financialPolicy->type ?? '') === 'absence' ? 'selected' : '' }}>غياب</option>
                <option value="early_leave" {{ old('type', $financialPolicy->type ?? '') === 'early_leave' ? 'selected' : '' }}>خروج مبكر</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">من دقيقة</label>
            <input type="number" name="minutes_from" value="{{ old('minutes_from', $financialPolicy->minutes_from ?? '') }}"
                   class="w-full border border-gray-300 rounded-xl px-4 py-2.5">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">إلى دقيقة</label>
            <input type="number" name="minutes_to" value="{{ old('minutes_to', $financialPolicy->minutes_to ?? '') }}"
                   class="w-full border border-gray-300 rounded-xl px-4 py-2.5">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">نوع الجزاء *</label>
            <select name="penalty_type" class="w-full border border-gray-300 rounded-xl px-4 py-2.5">
                <option value="fixed" {{ old('penalty_type', $financialPolicy->penalty_type ?? '') === 'fixed' ? 'selected' : '' }}>مبلغ ثابت</option>
                <option value="percent" {{ old('penalty_type', $financialPolicy->penalty_type ?? '') === 'percent' ? 'selected' : '' }}>نسبة</option>
                <option value="warning" {{ old('penalty_type', $financialPolicy->penalty_type ?? '') === 'warning' ? 'selected' : '' }}>إنذار</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">قيمة الجزاء</label>
            <input type="number" step="0.01" name="penalty_value" value="{{ old('penalty_value', $financialPolicy->penalty_value ?? '') }}"
                   class="w-full border border-gray-300 rounded-xl px-4 py-2.5">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">ملاحظات</label>
        <textarea name="notes" rows="3"
                  class="w-full border border-gray-300 rounded-xl px-4 py-2.5">{{ old('notes', $financialPolicy->notes ?? '') }}</textarea>
    </div>

    <div>
        <label class="flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1"
                {{ old('is_active', isset($financialPolicy) ? $financialPolicy->is_active : true) ? 'checked' : '' }}>
            <span class="text-sm">نشطة</span>
        </label>
    </div>
</div>