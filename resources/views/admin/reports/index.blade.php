@extends('layouts.admin')

@section('page-title', 'مركز التقارير')
@section('page-subtitle', 'عرض وتصدير التقارير الإجمالية والتفصيلية')

@section('content')
@php
$reportType = request('report_type', $reportType ?? 'summary');

$reportCards = [
[
'key' => 'summary',
'title' => 'تقرير إجمالي',
'desc' => 'ملخص عام للحضور والانصراف والإجازات.',
'status' => 'active',
],
[
'key' => 'detailed',
'title' => 'تقرير تفصيلي',
'desc' => 'تفاصيل يومية كاملة لكل موظف.',
'status' => 'active',
],
[
'key' => 'daily',
'title' => 'تقرير يومي',
'desc' => 'تقرير خاص بيوم محدد للحضور والانصراف.',
'status' => 'active',
],
[
'key' => 'leaves',
'title' => 'تقرير الإجازات',
'desc' => 'متابعة طلبات الإجازات واعتمادها.',
'status' => 'active',
],
[
'key' => 'monthly_leaves',
'title' => 'تقرير الإجازات الشهرية',
'desc' => 'إحصائيات شهرية للإجازات لكل موظف أو قسم.',
'status' => 'active',
],
[
'key' => 'audit',
'title' => 'تقرير التعديلات',
'desc' => 'سجل بالتعديلات التي تمت على الطلبات والحضور.',
'status' => 'active',
],
];

$auditActions = [
'approve_leave_request' => 'اعتماد إجازة',
'reject_leave_request' => 'رفض إجازة',
'approve_permission' => 'اعتماد إذن',
'reject_permission' => 'رفض إذن',
'approve_mission' => 'اعتماد مهمة',
'reject_mission' => 'رفض مهمة',
'approve_comp_off' => 'اعتماد بدل راحة',
'reject_comp_off' => 'رفض بدل راحة',
'approve_shift_change' => 'اعتماد تغيير وردية',
'reject_shift_change' => 'رفض تغيير وردية',
];

$targetTypes = [
'leave_request' => 'طلب إجازة',
'permission' => 'إذن',
'mission' => 'مهمة',
'comp_off' => 'بدل راحة',
'shift_change' => 'تغيير وردية',
];
@endphp

<div class="space-y-6">

    <div class="bg-white rounded-2xl shadow-sm p-6">
        <form method="GET" action="{{ route('admin.reports.index') }}">
            <div class="flex flex-col md:flex-row md:items-end gap-4">
                <div class="flex-1">
                    <label class="block text-sm text-gray-600 mb-1">نوع التقرير</label>
                    <select name="report_type" class="w-full border border-gray-300 rounded-xl px-4 py-2.5">
                        <option value="summary" {{ $reportType === 'summary' ? 'selected' : '' }}>تقرير إجمالي</option>
                        <option value="detailed" {{ $reportType === 'detailed' ? 'selected' : '' }}>تقرير تفصيلي</option>
                        <option value="daily" {{ $reportType === 'daily' ? 'selected' : '' }}>تقرير يومي</option>
                        <option value="leaves" {{ $reportType === 'leaves' ? 'selected' : '' }}>تقرير الإجازات</option>
                        <option value="monthly_leaves" {{ $reportType === 'monthly_leaves' ? 'selected' : '' }}>تقرير الإجازات الشهرية</option>
                        <option value="audit" {{ $reportType === 'audit' ? 'selected' : '' }}>تقرير التعديلات</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">من تاريخ</label>
                    <input type="date" name="from" value="{{ request('from', $from ?? '') }}" class="w-full border border-gray-300 rounded-xl px-4 py-2.5">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">إلى تاريخ</label>
                    <input type="date" name="to" value="{{ request('to', $to ?? '') }}" class="w-full border border-gray-300 rounded-xl px-4 py-2.5">
                </div>


                <div>
                    <label class="block text-sm text-gray-600 mb-1">الموظف</label>
                    <select name="employee_id" class="w-full border border-gray-300 rounded-xl px-4 py-2.5">
                        <option value="">كل الموظفين</option>

                        @foreach($employees as $employee)
                        <option value="{{ $employee->id }}"
                            {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->user->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition">
                        عرض
                    </button>

                    <a href="{{ route('admin.reports.export.excel', request()->query()) }}"
                        class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition">
                        تصدير Excel
                    </a>

                    <a href="{{ route('admin.reports.export.pdf', request()->query()) }}"
                        class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition">
                        تصدير PDF
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @foreach($reportCards as $card)
        <a href="{{ route('admin.reports.index', array_merge(request()->query(), ['report_type' => $card['key']])) }}"
            class="bg-white rounded-2xl shadow-sm p-5 hover:shadow-md transition border {{ $reportType === $card['key'] ? 'border-blue-400 ring-2 ring-blue-100' : 'border-gray-100' }}">
            <h3 class="font-semibold text-gray-800 mb-2">{{ $card['title'] }}</h3>
            <p class="text-sm text-gray-500">{{ $card['desc'] }}</p>

            @if($card['status'] === 'active')
            <span class="inline-block mt-3 bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full">متاح</span>
            @else
            <span class="inline-block mt-3 bg-yellow-100 text-yellow-700 text-xs px-3 py-1 rounded-full">قيد التطوير</span>
            @endif
        </a>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-6">
        @if($reportType === 'leaves')
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800">نتائج تقرير الإجازات</h3>
            <span class="text-sm text-gray-500">عدد النتائج: {{ $rows->count() }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-600">
                        <th class="text-right py-3 px-4 font-semibold">الموظف</th>
                        <th class="text-right py-3 px-4 font-semibold">نوع الإجازة</th>
                        <th class="text-right py-3 px-4 font-semibold">من تاريخ</th>
                        <th class="text-right py-3 px-4 font-semibold">إلى تاريخ</th>
                        <th class="text-right py-3 px-4 font-semibold">عدد الأيام</th>
                        <th class="text-right py-3 px-4 font-semibold">الحالة</th>
                        <th class="text-right py-3 px-4 font-semibold">المعتمد بواسطة</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($rows as $row)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4">{{ $row->employee->user->name ?? '-' }}</td>
                        <td class="py-3 px-4">{{ $row->leaveType->name_ar ?? $row->leaveType->name ?? '-' }}</td>
                        <td class="py-3 px-4">{{ $row->start_date?->format('Y/m/d') }}</td>
                        <td class="py-3 px-4">{{ $row->end_date?->format('Y/m/d') }}</td>
                        <td class="py-3 px-4">{{ $row->days_count }}</td>
                        <td class="py-3 px-4">{{ $row->status_label ?? $row->status }}</td>
                        <td class="py-3 px-4">{{ $row->approvedBy->name ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-10 text-gray-400">لا توجد نتائج</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @elseif($reportType === 'audit')
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800">نتائج تقرير التعديلات</h3>
            <span class="text-sm text-gray-500">عدد النتائج: {{ $rows->count() }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-600">
                        <th class="text-right py-3 px-4 font-semibold">المستخدم</th>
                        <th class="text-right py-3 px-4 font-semibold">العملية</th>
                        <th class="text-right py-3 px-4 font-semibold">نوع العنصر</th>
                        <th class="text-right py-3 px-4 font-semibold">العنصر</th>
                        <th class="text-right py-3 px-4 font-semibold">الوصف</th>
                        <th class="text-right py-3 px-4 font-semibold">التاريخ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($rows as $row)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4">{{ $row->user?->name ?? 'النظام' }}</td>
                        <td class="py-3 px-4">{{ $auditActions[$row->action] ?? $row->action }}</td>
                        <td class="py-3 px-4">{{ $targetTypes[$row->target_type] ?? ($row->target_type ?? '-') }}</td>
                        <td class="py-3 px-4">{{ $row->target_name ?? '-' }}</td>
                        <td class="py-3 px-4">{{ $row->description ?? '-' }}</td>
                        <td class="py-3 px-4">{{ $row->created_at?->format('Y/m/d H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-gray-400">لا توجد نتائج</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>



        @elseif($reportType === 'detailed')
        @php
        $days = [
        'Sunday' => 'الأحد',
        'Monday' => 'الاثنين',
        'Tuesday' => 'الثلاثاء',
        'Wednesday' => 'الأربعاء',
        'Thursday' => 'الخميس',
        'Friday' => 'الجمعة',
        'Saturday' => 'السبت',
        ];

        $statusMap = [
        'present' => 'حاضر',
        'late' => 'متأخر',
        'absent' => 'غائب',
        'leave' => 'إجازة',
        'holiday' => 'عطلة',
        'incomplete' => 'غير مكتمل',
        ];
        @endphp

        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800">نتائج التقرير التفصيلي</h3>
            <span class="text-sm text-gray-500">عدد النتائج: {{ $rows->count() }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-600">
                        <th class="text-right py-3 px-4 font-semibold">الموظف</th>
                        <th class="text-right py-3 px-4 font-semibold">القسم</th>
                        <th class="text-right py-3 px-4 font-semibold">التاريخ</th>
                        <th class="text-right py-3 px-4 font-semibold">اليوم</th>
                        <th class="text-right py-3 px-4 font-semibold">الحضور</th>
                        <th class="text-right py-3 px-4 font-semibold">الانصراف</th>
                        <th class="text-right py-3 px-4 font-semibold">الحالة</th>
                        <th class="text-right py-3 px-4 font-semibold">دقائق التأخير</th>
                        <th class="text-right py-3 px-4 font-semibold">ساعات العمل</th>
                        <th class="text-right py-3 px-4 font-semibold">ملاحظات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($rows as $row)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4">{{ $row->employee->user->name ?? '-' }}</td>
                        <td class="py-3 px-4">{{ $row->employee->department->name_ar ?? $row->employee->department->name ?? '-' }}</td>
                        <td class="py-3 px-4">{{ $row->date?->format('Y/m/d') }}</td>
                        <td class="py-3 px-4">{{ $days[$row->date?->format('l')] ?? '-' }}</td>
                        <td class="py-3 px-4">{{ $row->check_in?->format('H:i') ?? '-' }}</td>
                        <td class="py-3 px-4">{{ $row->check_out?->format('H:i') ?? '-' }}</td>
                        <td class="py-3 px-4">
                            <span class="
                                        px-3 py-1 rounded-full text-xs font-medium
                                        @if($row->status == 'present') bg-green-100 text-green-700
                                        @elseif($row->status == 'late') bg-yellow-100 text-yellow-700
                                        @elseif($row->status == 'absent') bg-red-100 text-red-700
                                        @elseif($row->status == 'leave') bg-blue-100 text-blue-700
                                        @else bg-gray-100 text-gray-700
                                        @endif
                                    ">
                                {{ $statusMap[$row->status] ?? $row->status }}
                            </span>
                        </td>
                        <td class="py-3 px-4">{{ $row->late_minutes ?? 0 }}</td>
                        <td class="py-3 px-4">{{ round(($row->work_minutes ?? 0) / 60, 1) }}</td>
                        <td class="py-3 px-4">{{ $row->notes ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-10 text-gray-400">لا توجد نتائج</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>


        @elseif($reportType === 'monthly_leaves')

        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800">تقرير الإجازات الشهرية</h3>
            <span class="text-sm text-gray-500">عدد النتائج: {{ $rows->count() }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-600">
                        <th class="text-right py-3 px-4 font-semibold">الموظف</th>
                        <th class="text-right py-3 px-4 font-semibold">القسم</th>
                        <th class="text-right py-3 px-4 font-semibold">الشهر</th>
                        <th class="text-right py-3 px-4 font-semibold">عدد مرات الإجازة</th>
                        <th class="text-right py-3 px-4 font-semibold">عدد أيام الإجازة</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($rows as $row)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4">{{ $row['employee_name'] }}</td>
                        <td class="py-3 px-4">{{ $row['department_name'] }}</td>
                        <td class="py-3 px-4">{{ $row['month'] }}</td>
                        <td class="py-3 px-4">{{ $row['requests_count'] }}</td>
                        <td class="py-3 px-4">{{ $row['days'] }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-10 text-gray-400">لا توجد بيانات</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @elseif($reportType === 'daily')
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800">نتائج التقرير اليومي</h3>
            <span class="text-sm text-gray-500">عدد النتائج: {{ $rows->count() }}</span>
        </div>


        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-600">
                        <th class="text-right py-3 px-4 font-semibold">الموظف</th>
                        <th class="text-right py-3 px-4 font-semibold">القسم</th>
                        <th class="text-right py-3 px-4 font-semibold">التاريخ</th>
                        <th class="text-right py-3 px-4 font-semibold">الحضور</th>
                        <th class="text-right py-3 px-4 font-semibold">الانصراف</th>
                        <th class="text-right py-3 px-4 font-semibold">الحالة</th>
                        <th class="text-right py-3 px-4 font-semibold">دقائق التأخير</th>
                        <th class="text-right py-3 px-4 font-semibold">ساعات العمل</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($rows as $row)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4">{{ $row->employee->user->name ?? '-' }}</td>
                        <td class="py-3 px-4">{{ $row->employee->department->name_ar ?? $row->employee->department->name ?? '-' }}</td>
                        <td class="py-3 px-4">{{ $row->date?->format('Y/m/d') }}</td>
                        <td class="py-3 px-4">{{ $row->check_in?->format('H:i') ?? '-' }}</td>
                        <td class="py-3 px-4">{{ $row->check_out?->format('H:i') ?? '-' }}</td>
                        <td>
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

                            <span class="
        px-3 py-1 rounded-full text-xs font-medium
        @if($row->status == 'present') bg-green-100 text-green-700
        @elseif($row->status == 'late') bg-yellow-100 text-yellow-700
        @elseif($row->status == 'absent') bg-red-100 text-red-700
        @elseif($row->status == 'leave') bg-blue-100 text-blue-700
        @else bg-gray-100 text-gray-700
        @endif
    ">
                                {{ $statusMap[$row->status] ?? $row->status }}
                            </span>
                        </td>
                        <td class="py-3 px-4">{{ $row->late_minutes ?? 0 }}</td>
                        <td class="py-3 px-4">{{ round(($row->work_minutes ?? 0) / 60, 1) }}</td>


                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-10 text-gray-400">لا توجد نتائج</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @elseif($reportType === 'summary')
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800">نتائج التقرير الإجمالي</h3>
            <span class="text-sm text-gray-500">عدد النتائج: {{ $rows->count() }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-600">
                        <th class="text-right py-3 px-4 font-semibold">الموظف</th>
                        <th class="text-right py-3 px-4 font-semibold">القسم</th>
                        <th class="text-right py-3 px-4 font-semibold">أيام الحضور</th>
                        <th class="text-right py-3 px-4 font-semibold">أيام التأخير</th>
                        <th class="text-right py-3 px-4 font-semibold">أيام الغياب</th>
                        <th class="text-right py-3 px-4 font-semibold">أيام الإجازات</th>
                        <th class="text-right py-3 px-4 font-semibold">إجمالي ساعات العمل</th>
                        <th class="text-right py-3 px-4 font-semibold">الأذون</th>
                        <th class="text-right py-3 px-4 font-semibold">المهمات</th>
                        <th class="text-right py-3 px-4 font-semibold">بدل الراحة</th>
                        <th class="text-right py-3 px-4 font-semibold">تغيير الوردية</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($rows as $row)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4">{{ $row['employee_name'] }}</td>
                        <td class="py-3 px-4">{{ $row['department_name'] }}</td>
                        <td class="py-3 px-4">{{ $row['present_count'] }}</td>
                        <td class="py-3 px-4">{{ $row['late_count'] }}</td>
                        <td class="py-3 px-4">{{ $row['absent_count'] }}</td>
                        <td class="py-3 px-4">{{ $row['leave_count'] }}</td>
                        <td class="py-3 px-4">{{ $row['work_hours'] }}</td>
                        <td class="py-3 px-4">{{ $row['permissions_count'] }}</td>
                        <td class="py-3 px-4">{{ $row['missions_count'] }}</td>
                        <td class="py-3 px-4">{{ $row['comp_off_count'] }}</td>
                        <td class="py-3 px-4">{{ $row['shift_changes_count'] }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-10 text-gray-400">لا توجد نتائج</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @else
        <div class="text-center py-10">
            <h3 class="text-lg font-bold text-gray-800 mb-3">هذا التقرير قيد التطوير</h3>
            <p class="text-sm text-gray-500">التقارير المتاحة حاليًا: الإجمالي، الإجازات، التعديلات.</p>
        </div>
        @endif
    </div>

</div>
@endsection