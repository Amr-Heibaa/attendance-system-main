@extends('layouts.employee')

@section('title', 'طلب مهمة جديد')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-sm p-8">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('employee.missions.index') }}" class="text-gray-400 hover:text-gray-600">←</a>
        <h2 class="text-xl font-bold text-gray-800">طلب مهمة جديد</h2>
    </div>

    <form method="POST" action="{{ route('employee.missions.store') }}">
        @csrf

        <div class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">عنوان المهمة *</label>
                <input type="text" name="title" value="{{ old('title') }}"
                    class="w-full border border-gray-300 rounded-xl px-4 py-2.5 @error('title') border-red-400 @enderror">
                @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">نوع المهمة *</label>
                <select name="type" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 @error('type') border-red-400 @enderror">
                    <option value="">-- اختر النوع --</option>
                    <option value="internal" {{ old('type') === 'internal' ? 'selected' : '' }}>مهمة داخلية</option>
                    <option value="external" {{ old('type') === 'external' ? 'selected' : '' }}>مهمة خارجية</option>
                </select>
                @error('type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">التاريخ *</label>
                <input type="date" name="date" value="{{ old('date') }}" min="{{ now()->toDateString() }}"
                    class="w-full border border-gray-300 rounded-xl px-4 py-2.5 @error('date') border-red-400 @enderror">
                @error('date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">من وقت *</label>
                    <input type="time" name="from_time" value="{{ old('from_time') }}"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 @error('from_time') border-red-400 @enderror">
                    @error('from_time')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">إلى وقت *</label>
                    <input type="time" name="to_time" value="{{ old('to_time') }}"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 @error('to_time') border-red-400 @enderror">
                    @error('to_time')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">الموقع</label>
                <input type="text" name="location" value="{{ old('location') }}"
                    class="w-full border border-gray-300 rounded-xl px-4 py-2.5 @error('location') border-red-400 @enderror">
                @error('location')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">سبب المهمة *</label>
                <textarea name="reason" rows="4"
                    class="w-full border border-gray-300 rounded-xl px-4 py-2.5 @error('reason') border-red-400 @enderror">{{ old('reason') }}</textarea>
                @error('reason')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-semibold transition">
                    تقديم الطلب
                </button>
                <a href="{{ route('employee.missions.index') }}" class="flex-1 text-center border border-gray-300 hover:bg-gray-50 text-gray-700 py-3 rounded-xl font-medium transition">
                    إلغاء
                </a>
            </div>
        </div>
    </form>
</div>
@endsection