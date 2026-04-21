@extends('layouts.admin')

@section('page-title', 'تفاصيل طلب المهمة')

@section('content')
<div class="max-w-2xl space-y-6">

    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-base font-semibold text-gray-700 mb-4 pb-2 border-b">بيانات الطلب</h3>
        <dl class="space-y-3 text-sm">
            <div class="flex justify-between">
                <dt class="text-gray-500">الموظف:</dt>
                <dd class="font-medium text-gray-800">{{ $missionRequest->employee->user->name }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">القسم:</dt>
                <dd class="text-gray-800">{{ $missionRequest->employee->department?->name_ar ?? '-' }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">العنوان:</dt>
                <dd class="text-gray-800">{{ $missionRequest->title }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">النوع:</dt>
                <dd class="text-gray-800">{{ $missionRequest->type_label }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">التاريخ:</dt>
                <dd class="text-gray-800">{{ $missionRequest->date->format('Y/m/d') }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">الوقت:</dt>
                <dd class="text-gray-800">
                    {{ \Carbon\Carbon::parse($missionRequest->from_time)->format('H:i') }}
                    -
                    {{ \Carbon\Carbon::parse($missionRequest->to_time)->format('H:i') }}
                </dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">المدة:</dt>
                <dd class="text-gray-800">{{ $missionRequest->minutes_count }} دقيقة</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">الموقع:</dt>
                <dd class="text-gray-800">{{ $missionRequest->location ?? '-' }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">السبب:</dt>
                <dd class="text-gray-800">{{ $missionRequest->reason }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">الحالة:</dt>
                <dd>{{ $missionRequest->status_label }}</dd>
            </div>
        </dl>
    </div>

    @if($missionRequest->status === 'pending')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div class="bg-green-50 border border-green-200 rounded-2xl p-5">
            <h4 class="font-semibold text-green-800 mb-3">اعتماد المهمة</h4>
            <form method="POST" action="{{ route('admin.mission-requests.approve', $missionRequest) }}">
                @csrf
                <textarea name="manager_notes" rows="2" placeholder="ملاحظات (اختياري)..."
                    class="w-full border border-green-200 rounded-xl px-3 py-2 text-sm mb-3 resize-none bg-white"></textarea>
                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white py-2.5 rounded-xl font-medium text-sm transition">
                    ✓ اعتماد
                </button>
            </form>
        </div>

        <div class="bg-red-50 border border-red-200 rounded-2xl p-5">
            <h4 class="font-semibold text-red-800 mb-3">رفض المهمة</h4>
            <form method="POST" action="{{ route('admin.mission-requests.reject', $missionRequest) }}">
                @csrf
                <textarea name="manager_notes" rows="2" placeholder="سبب الرفض..."
                    class="w-full border border-red-200 rounded-xl px-3 py-2 text-sm mb-3 resize-none bg-white"></textarea>
                <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white py-2.5 rounded-xl font-medium text-sm transition">
                    ✗ رفض
                </button>
            </form>
        </div>

    </div>
    @elseif($missionRequest->manager_notes)
    <div class="bg-white rounded-2xl shadow-sm p-5">
        <p class="text-sm text-gray-500 mb-1">ملاحظات الإدارة:</p>
        <p class="text-gray-800">{{ $missionRequest->manager_notes }}</p>
    </div>
    @endif

    <a href="{{ route('admin.mission-requests.index') }}" class="inline-block text-blue-600 hover:underline text-sm">
        ← العودة لقائمة الطلبات
    </a>
</div>
@endsection