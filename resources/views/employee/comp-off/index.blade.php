@extends('layouts.employee')

@section('title', 'بدل الراحة')

@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">طلبات بدل الراحة</h2>
        <a href="{{ route('employee.comp-off.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition">
            + طلب جديد
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600">
                    <th class="text-right py-3 px-4 font-semibold">تاريخ العمل</th>
                    <th class="text-right py-3 px-4 font-semibold">تاريخ بدل الراحة</th>
                    <th class="text-right py-3 px-4 font-semibold">السبب</th>
                    <th class="text-right py-3 px-4 font-semibold">الحالة</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($requests as $req)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4 text-gray-700">{{ $req->worked_on_date->format('Y/m/d') }}</td>
                    <td class="py-3 px-4 text-gray-700">{{ $req->requested_off_date->format('Y/m/d') }}</td>
                    <td class="py-3 px-4 text-gray-700">{{ $req->reason }}</td>
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
                    <td colspan="4" class="text-center py-12 text-gray-400">لا توجد طلبات بدل راحة</td>
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