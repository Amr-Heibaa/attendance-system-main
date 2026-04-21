@extends('layouts.admin')

@section('page-title', 'إضافة نوع إجازة')

@section('content')
<div class="max-w-2xl bg-white rounded-2xl shadow-sm p-8">
    <form method="POST" action="{{ route('admin.leave-types.store') }}">
        @csrf
        <div class="space-y-5">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">الاسم (EN) *</label>
                    <input name="name" value="{{ old('name') }}" class="w-full border rounded-xl px-4 py-2.5 @error('name') border-red-400 @enderror">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">الاسم (AR) *</label>
                    <input name="name_ar" value="{{ old('name_ar') }}" class="w-full border rounded-xl px-4 py-2.5 @error('name_ar') border-red-400 @enderror">
                    @error('name_ar')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">الحد الأقصى/سنة *</label>
                    <input type="number" name="max_days_per_year" value="{{ old('max_days_per_year', 0) }}"
                        class="w-full border rounded-xl px-4 py-2.5 @error('max_days_per_year') border-red-400 @enderror">
                    @error('max_days_per_year')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex items-end gap-6">
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="is_paid" value="1" {{ old('is_paid', 1) ? 'checked' : '' }}>
                        مدفوعة
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                        نشطة
                    </label>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-semibold">حفظ</button>
                <a href="{{ route('admin.leave-types.index') }}" class="flex-1 text-center border py-3 rounded-xl">إلغاء</a>
            </div>
        </div>
    </form>
</div>
@endsection