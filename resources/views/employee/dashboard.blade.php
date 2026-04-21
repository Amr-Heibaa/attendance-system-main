@extends('layouts.employee')

@section('title', 'الرئيسية')

@section('content')
<div class="space-y-6">
    <!-- Welcome Card -->
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">مرحباً، {{ auth()->user()->name }}</h2>
                <p class="text-gray-500 mt-1">{{ now()->locale('ar')->isoFormat('dddd، D MMMM YYYY') }}</p>
                @if($employee->department)
                    <p class="text-blue-600 text-sm mt-1">{{ $employee->department->name_ar }} | {{ $employee->job_title }}</p>
                @endif
            </div>
            <div class="text-5xl text-blue-100">
                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
        </div>
    </div>

    <!-- Attendance Status Card -->
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4 pb-2 border-b">حالة الحضور اليوم</h3>

        @if(!$todayRecord)
            <!-- No check-in yet -->
            <div class="text-center py-6">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-gray-500 text-lg mb-6">لم يتم تسجيل الحضور بعد</p>
                <form method="POST" action="{{ route('employee.check-in') }}" onsubmit="this.querySelector('button').disabled=true">
                    @csrf
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-8 py-3 rounded-xl font-semibold text-lg transition shadow-md hover:shadow-lg">
                        تسجيل الحضور الآن
                    </button>
                </form>
            </div>

        @elseif($todayRecord->check_in && !$todayRecord->check_out)
            <!-- Checked in, not out yet -->
            <div class="flex items-center gap-4 mb-6">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full">
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-green-700 font-semibold text-lg">تم تسجيل الحضور</p>
                    <p class="text-gray-600">الساعة {{ $todayRecord->check_in->format('h:i A') }}</p>
                    @if($todayRecord->status === 'late')
                        <span class="inline-block bg-yellow-100 text-yellow-700 text-xs px-2 py-0.5 rounded-full mt-1">
                            متأخر {{ $todayRecord->late_minutes }} دقيقة
                        </span>
                    @endif
                </div>
            </div>
            <form method="POST" action="{{ route('employee.check-out') }}" onsubmit="this.querySelector('button').disabled=true">
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-8 py-3 rounded-xl font-semibold text-lg transition shadow-md hover:shadow-lg">
                    تسجيل الانصراف الآن
                </button>
            </form>

        @else
            <!-- Fully checked out -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-green-50 rounded-xl p-4 text-center">
                    <p class="text-green-600 text-sm font-medium mb-1">وقت الحضور</p>
                    <p class="text-gray-800 font-bold text-lg">{{ $todayRecord->check_in?->format('h:i A') ?? '-' }}</p>
                </div>
                <div class="bg-blue-50 rounded-xl p-4 text-center">
                    <p class="text-blue-600 text-sm font-medium mb-1">وقت الانصراف</p>
                    <p class="text-gray-800 font-bold text-lg">{{ $todayRecord->check_out?->format('h:i A') ?? '-' }}</p>
                </div>
                <div class="bg-purple-50 rounded-xl p-4 text-center">
                    <p class="text-purple-600 text-sm font-medium mb-1">ساعات العمل</p>
                    <p class="text-gray-800 font-bold text-lg">{{ round($todayRecord->work_minutes / 60, 1) }} س</p>
                </div>
                <div class="bg-orange-50 rounded-xl p-4 text-center">
                    <p class="text-orange-600 text-sm font-medium mb-1">الحالة</p>
                    <p class="font-bold text-lg
                        @if($todayRecord->status === 'present') text-green-600
                        @elseif($todayRecord->status === 'late') text-yellow-600
                        @else text-gray-600
                        @endif">
                        {{ $todayRecord->status_label }}
                    </p>
                </div>
            </div>
            <p class="text-center text-gray-500 mt-4">تم تسجيل الانصراف بنجاح اليوم ✓</p>
        @endif
    </div>

    <!-- Quick Stats - Last 7 days -->
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4 pb-2 border-b">إحصائيات الأسبوع الماضي</h3>
        @php
            $weekRecords = $employee->attendanceRecords()
                ->where('date', '>=', now()->subDays(7))
                ->get();
            $presentDays = $weekRecords->whereIn('status', ['present', 'late'])->count();
            $lateDays = $weekRecords->where('status', 'late')->count();
            $totalWorkHours = round($weekRecords->sum('work_minutes') / 60, 1);
        @endphp
        <div class="grid grid-cols-3 gap-4">
            <div class="text-center">
                <p class="text-3xl font-bold text-green-600">{{ $presentDays }}</p>
                <p class="text-gray-500 text-sm mt-1">أيام حضور</p>
            </div>
            <div class="text-center">
                <p class="text-3xl font-bold text-yellow-500">{{ $lateDays }}</p>
                <p class="text-gray-500 text-sm mt-1">أيام تأخير</p>
            </div>
            <div class="text-center">
                <p class="text-3xl font-bold text-blue-600">{{ $totalWorkHours }}</p>
                <p class="text-gray-500 text-sm mt-1">ساعات عمل</p>
            </div>
        </div>
    </div>
</div>
@endsection