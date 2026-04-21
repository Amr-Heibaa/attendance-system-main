@extends('layouts.employee')

@section('title', 'طلب إجازة جديد')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-sm p-8">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('employee.leaves.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h2 class="text-xl font-bold text-gray-800">طلب إجازة جديد</h2>
    </div>

    <form method="POST" action="{{ route('employee.leaves.store') }}" onsubmit="this.querySelector('[type=submit]').disabled=true">
        @csrf

        <div class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">نوع الإجازة <span class="text-red-500">*</span></label>
                <select name="leave_type_id" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('leave_type_id') border-red-400 @enderror">
                    <option value="">-- اختر نوع الإجازة --</option>
                    @foreach($leaveTypes as $type)
                        <option value="{{ $type->id }}" {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->name_ar }} (حد {{ $type->max_days_per_year }} يوم/سنة)
                        </option>
                    @endforeach
                </select>
                @error('leave_type_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">من تاريخ <span class="text-red-500">*</span></label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}" min="{{ now()->toDateString() }}"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('start_date') border-red-400 @enderror">
                    @error('start_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">إلى تاريخ <span class="text-red-500">*</span></label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}" min="{{ now()->toDateString() }}"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('end_date') border-red-400 @enderror">
                    @error('end_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">سبب الإجازة <span class="text-red-500">*</span></label>
                <textarea name="reason" rows="4" placeholder="اكتب سبب الإجازة بالتفصيل..."
                    class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none @error('reason') border-red-400 @enderror">{{ old('reason') }}</textarea>
                @error('reason')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-semibold transition">
                    تقديم الطلب
                </button>
                <a href="{{ route('employee.leaves.index') }}" class="flex-1 text-center border border-gray-300 hover:bg-gray-50 text-gray-700 py-3 rounded-xl font-medium transition">
                    إلغاء
                </a>
            </div>
        </div>
    </form>
</div>
@endsection