@extends('layouts.admin')

@section('page-title', 'طلبات الإجازة')
@section('page-subtitle', 'مراجعة وإدارة طلبات الإجازة')

@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6">
    <!-- Filter -->
    <form method="GET" class="flex gap-3 mb-6">
        <select name="status" class="border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            <option value="">كل الحالات</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>قيد المراجعة</option>
            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>مقبولة</option>
            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>مرفوضة</option>
        </select>
        <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-xl text-sm transition">تصفية</button>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600">
                    <th class="text-right py-3 px-4 font-semibold">الموظف</th>
                    <th class="text-right py-3 px-4 font-semibold">القسم</th>
                    <th class="text-right py-3 px-4 font-semibold">نوع الإجازة</th>
                    <th class="text-right py-3 px-4 font-semibold">من</th>
                    <th class="text-right py-3 px-4 font-semibold">إلى</th>
                    <th class="text-right py-3 px-4 font-semibold">الأيام</th>
                    <th class="text-right py-3 px-4 font-semibold">الحالة</th>
                    <th class="text-right py-3 px-4 font-semibold">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($requests as $req)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4 font-medium text-gray-800">{{ $req->employee->user->name }}</td>
                    <td class="py-3 px-4 text-gray-600">{{ $req->employee->department?->name_ar ?? '-' }}</td>
                    <td class="py-3 px-4 text-gray-600">{{ $req->leaveType->name_ar }}</td>
                    <td class="py-3 px-4 text-gray-600">{{ $req->start_date->format('Y/m/d') }}</td>
                    <td class="py-3 px-4 text-gray-600">{{ $req->end_date->format('Y/m/d') }}</td>
                    <td class="py-3 px-4 text-gray-600">{{ $req->days_count }}</td>
                    <td class="py-3 px-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($req->status === 'approved') bg-green-100 text-green-700
                            @elseif($req->status === 'rejected') bg-red-100 text-red-700
                            @else bg-yellow-100 text-yellow-700 @endif">
                            {{ $req->status_label }}
                        </span>
                    </td>
                    <td class="py-3 px-4">
                        <a href="{{ route('admin.leave-requests.show', $req) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                            عرض التفاصيل
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-12 text-gray-400">لا توجد طلبات إجازة</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $requests->withQueryString()->links() }}</div>
</div>
@endsection