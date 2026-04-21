@extends('layouts.employee')

@section('title', 'الأذون')

@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">طلبات الأذون</h2>
        <a href="{{ route('employee.permissions.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition">
            + طلب إذن جديد
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600">
                    <th class="text-right py-3 px-4 font-semibold">التاريخ</th>
                    <th class="text-right py-3 px-4 font-semibold">النوع</th>
                    <th class="text-right py-3 px-4 font-semibold">من</th>
                    <th class="text-right py-3 px-4 font-semibold">إلى</th>
                    <th class="text-right py-3 px-4 font-semibold">المدة</th>
                    <th class="text-right py-3 px-4 font-semibold">الحالة</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($requests as $req)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4 text-gray-700">{{ $req->date->format('Y/m/d') }}</td>
                    <td class="py-3 px-4 text-gray-700">{{ $req->type_label }}</td>
                    <td class="py-3 px-4 text-gray-700">{{ \Carbon\Carbon::parse($req->from_time)->format('H:i') }}</td>
                    <td class="py-3 px-4 text-gray-700">{{ \Carbon\Carbon::parse($req->to_time)->format('H:i') }}</td>
                    <td class="py-3 px-4 text-gray-700">{{ $req->minutes_count }} دقيقة</td>
                    <td class="py-3 px-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($req->status === 'approved') bg-green-100 text-green-700
                            @elseif($req->status === 'rejected') bg-red-100 text-red-700
                            @else bg-yellow-100 text-yellow-700
                            @endif">
                            {{ $req->status_label }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-12 text-gray-400">لا توجد طلبات أذون</td>
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