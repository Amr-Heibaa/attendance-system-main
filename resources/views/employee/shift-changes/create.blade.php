@extends('layouts.employee')

@section('title', 'طلب تغيير وردية')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-sm p-8">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('employee.shift-changes.index') }}" class="text-gray-400 hover:text-gray-600">←</a>
        <h2 class="text-xl font-bold text-gray-800">طلب تغيير وردية</h2>
    </div>

    <form method="POST" action="{{ route('employee.shift-changes.store') }}">
        @csrf

        <div class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">الوردية الحالية</label>
                <input type="text"
                       value="{{ $employee->shift?->name ?? 'لا توجد وردية حالية' }}"
                       disabled
                       class="w-full border border-gray-300 bg-gray-50 rounded-xl px-4 py-2.5">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">الوردية المطلوبة *</label>
                <select name="requested_shift_id" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 @error('requested_shift_id') border-red-400 @enderror">
                    <option value="">-- اختر الوردية --</option>
                    @foreach($shifts as $shift)
                        <option value="{{ $shift->id }}" {{ old('requested_shift_id') == $shift->id ? 'selected' : '' }}>
                            {{ $shift->name }} ({{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }})
                        </option>
                    @endforeach
                </select>
                @error('requested_shift_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">تاريخ التنفيذ *</label>
                <input type="date" name="effective_date" value="{{ old('effective_date') }}" min="{{ now()->toDateString() }}"
                    class="w-full border border-gray-300 rounded-xl px-4 py-2.5 @error('effective_date') border-red-400 @enderror">
                @error('effective_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">السبب *</label>
                <textarea name="reason" rows="4"
                    class="w-full border border-gray-300 rounded-xl px-4 py-2.5 @error('reason') border-red-400 @enderror">{{ old('reason') }}</textarea>
                @error('reason')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-semibold transition">
                    تقديم الطلب
                </button>
                <a href="{{ route('employee.shift-changes.index') }}" class="flex-1 text-center border border-gray-300 hover:bg-gray-50 text-gray-700 py-3 rounded-xl font-medium transition">
                    إلغاء
                </a>
            </div>
        </div>
    </form>
</div>
@endsection