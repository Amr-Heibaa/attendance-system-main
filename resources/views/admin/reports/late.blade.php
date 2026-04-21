@extends('layouts.admin')

@section('page-title', 'تقرير المتأخرين')
@section('page-subtitle', 'عرض سجلات التأخير خلال فترة محددة')

@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">من تاريخ</label>
            <input type="date" name="from" value="{{ $from }}" class="w-full border rounded-xl px-4 py-2.5">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">إلى تاريخ</label>
            <input type="date" name="to" value="{{ $to }}" class="w-full border rounded-xl px-4 py-2.5">
        </div>
        <div class="flex items-end">
            <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-xl font-medium">عرض</button>
        </div>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm p-6">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600">
                    <th class="text-right py-3 px-4 font-semibold">التاريخ</th>
                    <th class="text-right py-3 px-4 font-semibold">الموظف</th>
                    <th class="text-right py-3 px-4 font-semibold">القسم</th>
                    <th class="text-right py-3 px-4 font-semibold">وقت الحضور</th>
                    <th class="text-right py-3 px-4 font-semibold">دقائق التأخير</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($records as $r)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4 text-gray-700">{{ $r->date->format('Y/m/d') }}</td>
                    <td class="py-3 px-4 font-medium text-gray-800">{{ $r->employee->user->name }}</td>
                    <td class="py-3 px-4 text-gray-600">{{ $r->employee->department?->name_ar ?? '-' }}</td>
                    <td class="py-3 px-4 text-gray-700">{{ $r->check_in?->format('h:i A') ?? '-' }}</td>
                    <td class="py-3 px-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                            {{ $r->late_minutes }} دقيقة
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-10 text-gray-400">لا توجد سجلات</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $records->withQueryString()->links() }}</div>
</div>
@endsection