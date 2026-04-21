@extends('layouts.admin')

@section('page-title', 'تعديل عطلة')

@section('content')
<div class="max-w-2xl bg-white rounded-2xl shadow-sm p-8">
    <form method="POST" action="{{ route('admin.holidays.update', $holiday) }}">
        @csrf @method('PUT')

        <div class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">التاريخ *</label>
                <input type="date" name="date" value="{{ old('date', $holiday->date->toDateString()) }}"
                    class="w-full border rounded-xl px-4 py-2.5 @error('date') border-red-400 @enderror">
                @error('date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">الاسم (EN) *</label>
                    <input name="name" value="{{ old('name', $holiday->name) }}"
                        class="w-full border rounded-xl px-4 py-2.5 @error('name') border-red-400 @enderror">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">الاسم (AR) *</label>
                    <input name="name_ar" value="{{ old('name_ar', $holiday->name_ar) }}"
                        class="w-full border rounded-xl px-4 py-2.5 @error('name_ar') border-red-400 @enderror">
                    @error('name_ar')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_recurring" value="1" id="is_recurring" {{ old('is_recurring', $holiday->is_recurring) ? 'checked' : '' }}>
                <label for="is_recurring" class="text-sm text-gray-700">تتكرر سنوياً</label>
            </div>

            <div class="flex gap-3 pt-2">
                <button class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-semibold">حفظ</button>
                <a href="{{ route('admin.holidays.index') }}" class="flex-1 text-center border py-3 rounded-xl">إلغاء</a>
            </div>
        </div>
    </form>
</div>
@endsection