@extends('layouts.admin')

@section('page-title', 'تفاصيل طلب بدل الراحة')

@section('content')
<div class="max-w-2xl space-y-6">

    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-base font-semibold text-gray-700 mb-4 pb-2 border-b">بيانات الطلب</h3>
        <dl class="space-y-3 text-sm">
            <div class="flex justify-between">
                <dt class="text-gray-500">الموظف:</dt>
                <dd class="font-medium text-gray-800">{{ $compOffRequest->employee->user->name }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">القسم:</dt>
                <dd class="text-gray-800">{{ $compOffRequest->employee->department?->name_ar ?? '-' }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">تاريخ العمل:</dt>
                <dd class="text-gray-800">{{ $compOffRequest->worked_on_date->format('Y/m/d') }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">تاريخ بدل الراحة:</dt>
                <dd class="text-gray-800">{{ $compOffRequest->requested_off_date->format('Y/m/d') }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">السبب:</dt>
                <dd class="text-gray-800">{{ $compOffRequest->reason }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">الحالة:</dt>
                <dd>{{ $compOffRequest->status_label }}</dd>
            </div>
        </dl>
    </div>

    @if($compOffRequest->status === 'pending')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div class="bg-green-50 border border-green-200 rounded-2xl p-5">
            <h4 class="font-semibold text-green-800 mb-3">اعتماد الطلب</h4>
            <form method="POST" action="{{ route('admin.comp-off-requests.approve', $compOffRequest) }}">
                @csrf
                <textarea name="manager_notes" rows="2" placeholder="ملاحظات (اختياري)..."
                    class="w-full border border-green-200 rounded-xl px-3 py-2 text-sm mb-3 resize-none bg-white"></textarea>
                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white py-2.5 rounded-xl font-medium text-sm transition">
                    ✓ اعتماد
                </button>
            </form>
        </div>

        <div class="bg-red-50 border border-red-200 rounded-2xl p-5">
            <h4 class="font-semibold text-red-800 mb-3">رفض الطلب</h4>
            <form method="POST" action="{{ route('admin.comp-off-requests.reject', $compOffRequest) }}">
                @csrf
                <textarea name="manager_notes" rows="2" placeholder="سبب الرفض..."
                    class="w-full border border-red-200 rounded-xl px-3 py-2 text-sm mb-3 resize-none bg-white"></textarea>
                <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white py-2.5 rounded-xl font-medium text-sm transition">
                    ✗ رفض
                </button>
            </form>
        </div>

    </div>
    @elseif($compOffRequest->manager_notes)
    <div class="bg-white rounded-2xl shadow-sm p-5">
        <p class="text-sm text-gray-500 mb-1">ملاحظات الإدارة:</p>
        <p class="text-gray-800">{{ $compOffRequest->manager_notes }}</p>
    </div>
    @endif

    <a href="{{ route('admin.comp-off-requests.index') }}" class="inline-block text-blue-600 hover:underline text-sm">
        ← العودة لقائمة الطلبات
    </a>
</div>
@endsection