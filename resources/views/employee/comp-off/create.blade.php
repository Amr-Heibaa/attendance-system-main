@extends('layouts.employee')

@section('title', 'طلب بدل راحة')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-sm p-8">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('employee.comp-off.index') }}" class="text-gray-400 hover:text-gray-600">←</a>
        <h2 class="text-xl font-bold text-gray-800">طلب بدل راحة</h2>
    </div>

    <form method="POST" action="{{ route('employee.comp-off.store') }}">
        @csrf

        <div class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">تاريخ العمل المطلوب تعويضه *</label>
                <input type="date" name="worked_on_date" value="{{ old('worked_on_date') }}"
                    class="w-full border border-gray-300 rounded-xl px-4 py-2.5 @error('worked_on_date') border-red-400 @enderror">
                @error('worked_on_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">تاريخ بدل الراحة المطلوب *</label>
                <input type="date" name="requested_off_date" value="{{ old('requested_off_date') }}" min="{{ now()->toDateString() }}"
                    class="w-full border border-gray-300 rounded-xl px-4 py-2.5 @error('requested_off_date') border-red-400 @enderror">
                @error('requested_off_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
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
                <a href="{{ route('employee.comp-off.index') }}" class="flex-1 text-center border border-gray-300 hover:bg-gray-50 text-gray-700 py-3 rounded-xl font-medium transition">
                    إلغاء
                </a>
            </div>
        </div>
    </form>
</div>
@endsection