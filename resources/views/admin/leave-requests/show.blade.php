@extends('layouts.admin')

@section('page-title', 'تفاصيل طلب الإجازة')

@section('content')
<div class="max-w-2xl space-y-6">
    <!-- Request Details -->
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-base font-semibold text-gray-700 mb-4 pb-2 border-b">بيانات الطلب</h3>
        <dl class="space-y-3 text-sm">
            <div class="flex justify-between">
                <dt class="text-gray-500">الموظف:</dt>
                <dd class="font-medium text-gray-800">{{ $leaveRequest->employee->user->name }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">نوع الإجازة:</dt>
                <dd class="text-gray-800">{{ $leaveRequest->leaveType->name_ar }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">من تاريخ:</dt>
                <dd class="text-gray-800">{{ $leaveRequest->start_date->format('Y/m/d') }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">إلى تاريخ:</dt>
                <dd class="text-gray-800">{{ $leaveRequest->end_date->format('Y/m/d') }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">عدد الأيام:</dt>
                <dd class="text-gray-800">{{ $leaveRequest->days_count }} أيام</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">السبب:</dt>
                <dd class="text-gray-800">{{ $leaveRequest->reason }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">الحالة:</dt>
                <dd>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($leaveRequest->status === 'approved') bg-green-100 text-green-700
                        @elseif($leaveRequest->status === 'rejected') bg-red-100 text-red-700
                        @else bg-yellow-100 text-yellow-700 @endif">
                        {{ $leaveRequest->status_label }}
                    </span>
                </dd>
            </div>
        </dl>
    </div>

    <!-- Approve / Reject -->
    @if($leaveRequest->status === 'pending')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Approve Form -->
        <div class="bg-green-50 border border-green-200 rounded-2xl p-5">
            <h4 class="font-semibold text-green-800 mb-3">قبول الطلب</h4>
            <form method="POST" action="{{ route('admin.leave-requests.approve', $leaveRequest) }}">
                @csrf
                <textarea name="manager_notes" rows="2" placeholder="ملاحظات (اختياري)..."
                    class="w-full border border-green-200 rounded-xl px-3 py-2 text-sm mb-3 focus:ring-2 focus:ring-green-400 focus:border-transparent resize-none bg-white"></textarea>
                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white py-2.5 rounded-xl font-medium text-sm transition">
                    ✓ قبول الطلب
                </button>
            </form>
        </div>

        <!-- Reject Form -->
        <div class="bg-red-50 border border-red-200 rounded-2xl p-5">
            <h4 class="font-semibold text-red-800 mb-3">رفض الطلب</h4>
            <form method="POST" action="{{ route('admin.leave-requests.reject', $leaveRequest) }}">
                @csrf
                <textarea name="manager_notes" rows="2" placeholder="سبب الرفض (مطلوب)..."
                    class="w-full border border-red-200 rounded-xl px-3 py-2 text-sm mb-3 focus:ring-2 focus:ring-red-400 focus:border-transparent resize-none bg-white @error('manager_notes') border-red-500 @enderror"></textarea>
                @error('manager_notes')<p class="text-red-600 text-xs mb-2">{{ $message }}</p>@enderror
                <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white py-2.5 rounded-xl font-medium text-sm transition">
                    ✗ رفض الطلب
                </button>
            </form>
        </div>
    </div>
    @elseif($leaveRequest->manager_notes)
    <div class="bg-white rounded-2xl shadow-sm p-5">
        <p class="text-sm text-gray-500 mb-1">ملاحظات الإدارة:</p>
        <p class="text-gray-800">{{ $leaveRequest->manager_notes }}</p>
        @if($leaveRequest->approvedBy)
            <p class="text-xs text-gray-400 mt-2">بواسطة: {{ $leaveRequest->approvedBy->name }} في {{ $leaveRequest->approved_at->format('Y/m/d h:i A') }}</p>
        @endif
    </div>
    @endif

    <a href="{{ route('admin.leave-requests.index') }}" class="inline-block text-blue-600 hover:underline text-sm">← العودة لقائمة الطلبات</a>
</div>
@endsection