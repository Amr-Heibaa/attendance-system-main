@extends('layouts.admin')

@section('page-title', 'تعديل موعد منتظم')
@section('page-subtitle', 'تحديث سياسة الدوام')

@section('content')
<div class="max-w-4xl bg-white rounded-2xl shadow-sm p-8">
    <form method="POST" action="{{ route('admin.work-schedules.update', $workSchedule) }}">
        @csrf
        @method('PUT')

        @include('admin.work-schedules._form')

        <div class="flex gap-4 pt-6">
            <button type="submit"
                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-semibold transition">
                حفظ التعديلات
            </button>

            <a href="{{ route('admin.work-schedules.index') }}"
                class="flex-1 text-center border border-gray-300 hover:bg-gray-50 text-gray-700 py-3 rounded-xl font-medium transition">
                إلغاء
            </a>
        </div>
    </form>
</div>
@endsection