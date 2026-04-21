@extends('layouts.admin')

@section('page-title', 'الحضور والانصراف')
@section('page-subtitle', 'متابعة حضور الموظفين اليوم')

@section('content')
<div class="space-y-6">

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl shadow text-center">
            <p class="text-gray-500 text-sm">حضور</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['present'] }}</p>
        </div>

        <div class="bg-white p-4 rounded-xl shadow text-center">
            <p class="text-gray-500 text-sm">متأخر</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['late'] }}</p>
        </div>

        <div class="bg-white p-4 rounded-xl shadow text-center">
            <p class="text-gray-500 text-sm">غياب</p>
            <p class="text-2xl font-bold text-red-600">{{ $stats['absent'] }}</p>
        </div>

        <div class="bg-white p-4 rounded-xl shadow text-center">
            <p class="text-gray-500 text-sm">غير مكتمل</p>
            <p class="text-2xl font-bold text-gray-700">{{ $stats['incomplete'] }}</p>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-3 text-right">الموظف</th>
                    <th class="p-3 text-right">الحضور</th>
                    <th class="p-3 text-right">الانصراف</th>
                    <th class="p-3 text-right">الحالة</th>
                    <th class="p-3 text-right">الجزاء المالي</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $r)
                <tr class="border-t">
                    <td class="p-3">{{ $r->employee->user->name }}</td>
                    <td class="p-3">{{ $r->check_in?->format('H:i') ?? '-' }}</td>
                    <td class="p-3">{{ $r->check_out?->format('H:i') ?? '-' }}</td>

                    <td class="p-3">
                        @php
                        $statusMap = [
                        'present' => 'حاضر',
                        'late' => 'متأخر',
                        'absent' => 'غائب',
                        'leave' => 'إجازة',
                        'holiday' => 'عطلة',
                        'incomplete' => 'غير مكتمل',
                        ];
                        @endphp

                        {{ $statusMap[$r->status] ?? $r->status }}
                    </td>

                    <td class="p-3 text-gray-600">
                        @if(!empty($r->financial_penalty))
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                            {{ $r->financial_penalty['label'] }}
                        </span>
                        @else
                        -
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center p-6 text-gray-400">
                        لا يوجد بيانات اليوم
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection