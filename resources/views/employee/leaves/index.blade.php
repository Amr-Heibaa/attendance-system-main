@extends('layouts.employee')

@section('title', 'طلبات الإجازة')

@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">طلبات الإجازة</h2>
        <a href="{{ route('employee.leaves.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition">
            + طلب إجازة جديد
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600">
                    <th class="text-right py-3 px-4 font-semibold">نوع الإجازة</th>
                    <th class="text-right py-3 px-4 font-semibold">من تاريخ</th>
                    <th class="text-right py-3 px-4 font-semibold">إلى تاريخ</th>
                    <th class="text-right py-3 px-4 font-semibold">عدد الأيام</th>
                    <th class="text-right py-3 px-4 font-semibold">السبب</th>
                    <th class="text-right py-3 px-4 font-semibold">الحالة</th>
                    <th class="text-right py-3 px-4 font-semibold">ملاحظات الإدارة</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($requests as $req)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4 font-medium text-gray-800">{{ $req->leaveType->name_ar }}</td>
                    <td class="py-3 px-4 text-gray-600">{{ $req->start_date->format('Y/m/d') }}</td>
                    <td class="py-3 px-4 text-gray-600">{{ $req->end_date->format('Y/m/d') }}</td>
                    <td class="py-3 px-4 text-gray-600">{{ $req->days_count }} أيام</td>
                    <td class="py-3 px-4 text-gray-600 max-w-xs truncate">{{ $req->reason }}</td>
                    <td class="py-3 px-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($req->status === 'approved') bg-green-100 text-green-700
                            @elseif($req->status === 'rejected') bg-red-100 text-red-700
                            @else bg-yellow-100 text-yellow-700
                            @endif">
                            {{ $req->status_label }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-gray-500 text-xs">{{ $req->manager_notes ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-12 text-gray-400">لا توجد طلبات إجازة</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $requests->links() }}
    </div>
</div>
@endsection