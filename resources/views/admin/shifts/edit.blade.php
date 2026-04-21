@extends('layouts.admin')

@section('page-title', 'تعديل وردية')

@section('content')
<div class="max-w-2xl bg-white rounded-2xl shadow-sm p-8">
    <form method="POST" action="{{ route('admin.shifts.update', $shift) }}">
        @csrf @method('PUT')

        <div class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">اسم الوردية *</label>
                <input name="name" value="{{ old('name', $shift->name) }}" class="w-full border rounded-xl px-4 py-2.5 @error('name') border-red-400 @enderror">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">وقت البدء *</label>
                    <input type="time" name="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($shift->start_time)->format('H:i')) }}"
                        class="w-full border rounded-xl px-4 py-2.5 @error('start_time') border-red-400 @enderror">
                    @error('start_time')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">وقت الانتهاء *</label>
                    <input type="time" name="end_time" value="{{ old('end_time', \Carbon\Carbon::parse($shift->end_time)->format('H:i')) }}"
                        class="w-full border rounded-xl px-4 py-2.5 @error('end_time') border-red-400 @enderror">
                    @error('end_time')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">دقائق السماح *</label>
                    <input type="number" name="grace_minutes" value="{{ old('grace_minutes', $shift->grace_minutes) }}"
                        class="w-full border rounded-xl px-4 py-2.5 @error('grace_minutes') border-red-400 @enderror">
                    @error('grace_minutes')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $shift->is_active) ? 'checked' : '' }}>
                <label for="is_active" class="text-sm text-gray-700">نشط</label>
            </div>

            <div class="flex gap-3 pt-2">
                <button class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-semibold">حفظ</button>
                <a href="{{ route('admin.shifts.index') }}" class="flex-1 text-center border py-3 rounded-xl">إلغاء</a>
            </div>
        </div>
    </form>
</div>
@endsection