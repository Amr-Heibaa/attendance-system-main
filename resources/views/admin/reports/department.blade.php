@extends('layouts.admin')

@section('page-title', 'تقرير الأقسام')
@section('page-subtitle', 'ملخص حضور الأقسام حسب الشهر')

@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">الشهر</label>
            <input type="month" name="month" value="{{ $month }}" class="w-full border rounded-xl px-4 py-2.5">
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
                    <th class="text-right py-3 px-4 font-semibold">القسم</th>
                    <th class="text-right py-3 px-4 font-semibold">عدد الموظفين</th>
                    <th class="text-right py-3 px-4 font-semibold">حضور</th>
                    <th class="text-right py-3 px-4 font-semibold">تأخير</th>
                    <th class="text-right py-3 px-4 font-semibold">غياب</th>
                    <th class="text-right py-3 px-4 font-semibold">إجازات</th>
                    <th class="text-right py-3 px-4 font-semibold">ساعات عمل</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($departments as $dept)
                    @php
                        $records = $dept->employees->flatMap(fn($e) => $e->attendanceRecords);
                        $present = $records->whereIn('status',['present','late'])->count();
                        $late    = $records->where('status','late')->count();
                        $absent  = $records->where('status','absent')->count();
                        $leave   = $records->where('status','leave')->count();
                        $hours   = round(($records->sum('work_minutes') ?? 0)/60, 1);
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4 font-medium text-gray-800">{{ $dept->name_ar ?? $dept->name }}</td>
                        <td class="py-3 px-4 text-gray-600">{{ $dept->employees->count() }}</td>
                        <td class="py-3 px-4 text-gray-600">{{ $present }}</td>
                        <td class="py-3 px-4 text-gray-600">{{ $late }}</td>
                        <td class="py-3 px-4 text-gray-600">{{ $absent }}</td>
                        <td class="py-3 px-4 text-gray-600">{{ $leave }}</td>
                        <td class="py-3 px-4 text-gray-600">{{ $hours }}</td>
                    </tr>
                @empty
                <tr><td colspan="7" class="text-center py-10 text-gray-400">لا توجد بيانات</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection