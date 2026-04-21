@extends('layouts.employee')

@section('title', 'سجل الحضور')

@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">سجل الحضور والانصراف</h2>
    </div>

    <!-- Filters -->
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 p-4 bg-gray-50 rounded-xl">
        <div>
            <label class="block text-sm text-gray-600 mb-1">من تاريخ</label>
            <input type="date" name="from" value="{{ request('from') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">إلى تاريخ</label>
            <input type="date" name="to" value="{{ request('to') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">الحالة</label>
            <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">الكل</option>
                <option value="present" {{ request('status') === 'present' ? 'selected' : '' }}>حاضر</option>
                <option value="late" {{ request('status') === 'late' ? 'selected' : '' }}>متأخر</option>
                <option value="absent" {{ request('status') === 'absent' ? 'selected' : '' }}>غائب</option>
                <option value="leave" {{ request('status') === 'leave' ? 'selected' : '' }}>إجازة</option>
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg text-sm font-medium transition">
                تصفية
            </button>
        </div>
    </form>

    <!-- Table -->
    <div class="overflow-x-auto">
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
                <tr class="hover:bg-gray-50 transition">
                    <td class="py-3 px-4 text-gray-700">{{ $record->date->format('Y/m/d') }}</td>
                    <td class="py-3 px-4 text-gray-500">{{ $record->date->locale('ar')->dayName }}</td>
                    <td class="py-3 px-4 text-gray-700">{{ $record->check_in?->format('h:i A') ?? '-' }}</td>
                    <td class="py-3 px-4 text-gray-700">{{ $record->check_out?->format('h:i A') ?? '-' }}</td>
                    <td class="py-3 px-4 text-gray-700">{{ $record->work_minutes > 0 ? round($record->work_minutes / 60, 1) . ' س' : '-' }}</td>
                    <td class="py-3 px-4 text-gray-700">{{ $record->late_minutes > 0 ? $record->late_minutes : '-' }}</td>
                    <td class="py-3 px-4">
                        @php
                            $colors = ['present' => 'green', 'late' => 'yellow', 'absent' => 'red', 'leave' => 'blue', 'holiday' => 'gray'];
                            $color = $colors[$record->status] ?? 'gray';
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $color }}-100 text-{{ $color }}-700">
                            {{ $record->status_label }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-12 text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/></svg>
                        لا توجد سجلات حضور
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $records->withQueryString()->links() }}
    </div>
</div>
@endsection