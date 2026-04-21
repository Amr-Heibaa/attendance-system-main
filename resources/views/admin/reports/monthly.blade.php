@extends('layouts.admin')

@section('page-title', 'التقرير الشهري للموظف')

@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">الموظف</label>
            <select name="employee_id" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                <option value="">-- اختر الموظف --</option>
                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                        {{ $emp->user->name }} ({{ $emp->employee_code }})
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">الشهر</label>
            <input type="month" name="month" value="{{ request('month', now()->format('Y-m')) }}"
                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-xl font-medium transition">
                عرض التقرير
            </button>
        </div>
    </form>
</div>

@isset($employee)
<!-- Summary Cards -->
<div class="grid grid-cols-5 gap-4 mb-6">
    <div class="bg-green-50 rounded-2xl p-4 text-center">
        <p class="text-3xl font-bold text-green-600">{{ $summary['present'] }}</p>
        <p class="text-xs text-gray-500 mt-1">أيام حضور</p>
    </div>
    <div class="bg-red-50 rounded-2xl p-4 text-center">
        <p class="text-3xl font-bold text-red-500">{{ $summary['absent'] }}</p>
        <p class="text-xs text-gray-500 mt-1">أيام غياب</p>
    </div>
    <div class="bg-yellow-50 rounded-2xl p-4 text-center">
        <p class="text-3xl font-bold text-yellow-500">{{ $summary['late'] }}</p>
        <p class="text-xs text-gray-500 mt-1">أيام تأخير</p>
    </div>
    <div class="bg-blue-50 rounded-2xl p-4 text-center">
        <p class="text-3xl font-bold text-blue-500">{{ $summary['leave'] }}</p>
        <p class="text-xs text-gray-500 mt-1">أيام إجازة</p>
    </div>
    <div class="bg-purple-50 rounded-2xl p-4 text-center">
        <p class="text-3xl font-bold text-purple-500">{{ $summary['total_work_hours'] }}</p>
        <p class="text-xs text-gray-500 mt-1">ساعات عمل</p>
    </div>
</div>

<!-- Export Buttons -->
<div class="flex gap-3 mb-4">
    <a href="{{ route('admin.reports.monthly.pdf', request()->all()) }}"
       target="_blank"
       class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition">
        عرض PDF للطباعة
    </a>
    <a href="{{ route('admin.reports.monthly.excel', request()->all()) }}"
       class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition">
        تصدير Excel
    </a>
</div>

<!-- Records Table -->
<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 text-gray-600">
                <th class="text-right py-3 px-4 font-semibold">التاريخ</th>
                <th class="text-right py-3 px-4 font-semibold">اليوم</th>
                <th class="text-right py-3 px-4 font-semibold">وقت الحضور</th>
                <th class="text-right py-3 px-4 font-semibold">وقت الانصراف</th>
                <th class="text-right py-3 px-4 font-semibold">ساعات العمل</th>
                <th class="text-right py-3 px-4 font-semibold">التأخير (د)</th>
                <th class="text-right py-3 px-4 font-semibold">الحالة</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($records as $record)
            <tr class="hover:bg-gray-50">
                <td class="py-3 px-4">{{ $record->date->format('Y/m/d') }}</td>
                <td class="py-3 px-4 text-gray-500">{{ $record->date->locale('ar')->dayName }}</td>
                <td class="py-3 px-4">{{ $record->check_in?->format('h:i A') ?? '-' }}</td>
                <td class="py-3 px-4">{{ $record->check_out?->format('h:i A') ?? '-' }}</td>
                <td class="py-3 px-4">{{ $record->work_minutes > 0 ? round($record->work_minutes / 60, 1) . ' س' : '-' }}</td>
                <td class="py-3 px-4">{{ $record->late_minutes > 0 ? $record->late_minutes : '-' }}</td>
                <td class="py-3 px-4">
                    @php $colors = ['present'=>'green','late'=>'yellow','absent'=>'red','leave'=>'blue','holiday'=>'gray']; @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $colors[$record->status]??'gray' }}-100 text-{{ $colors[$record->status]??'gray' }}-700">
                        {{ $record->status_label }}
                    </span>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center py-8 text-gray-400">لا توجد سجلات</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endisset
@endsection