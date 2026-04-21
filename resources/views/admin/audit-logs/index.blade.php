@extends('layouts.admin')

@section('page-title', 'تقرير التعديلات')
@section('page-subtitle', 'سجل العمليات والإجراءات التي تمت على النظام')

@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6">

    @php
        $actions = [
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

    <form method="GET" class="flex flex-col md:flex-row gap-3 mb-6">
        <div class="w-full md:w-72">
            <label class="block text-sm font-medium text-gray-600 mb-1.5">نوع العملية</label>
            <select name="action"
                    class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">كل العمليات</option>
                @foreach($actions as $key => $label)
                    <option value="{{ $key }}" {{ request('action') === $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-end gap-2">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition">
                بحث
            </button>

            <a href="{{ route('admin.audit-logs.index') }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-xl text-sm font-medium transition">
                إعادة تعيين
            </a>
        </div>
    </form>

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
                @forelse($logs as $log)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-3 px-4 text-gray-800 font-medium">
                            {{ $log->user?->name ?? 'النظام' }}
                        </td>

                        <td class="py-3 px-4">
                            <span class="
                                inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                {{ str_contains($log->action, 'approve') ? 'bg-green-100 text-green-700' : '' }}
                                {{ str_contains($log->action, 'reject') ? 'bg-red-100 text-red-700' : '' }}
                                {{ !str_contains($log->action, 'approve') && !str_contains($log->action, 'reject') ? 'bg-gray-100 text-gray-700' : '' }}
                            ">
                                {{ $actions[$log->action] ?? $log->action }}
                            </span>
                        </td>

                        <td class="py-3 px-4 text-gray-600">
                            {{ $targetTypes[$log->target_type] ?? ($log->target_type ?? '-') }}
                        </td>

                        <td class="py-3 px-4 text-gray-700">
                            {{ $log->target_name ?? '-' }}
                        </td>

                        <td class="py-3 px-4 text-gray-600">
                            {{ $log->description ?? '-' }}
                        </td>

                        <td class="py-3 px-4 text-gray-500 whitespace-nowrap">
                            {{ $log->created_at->format('Y/m/d H:i') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-12 text-gray-400">
                            لا توجد سجلات تعديلات
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $logs->withQueryString()->links() }}
    </div>
</div>
@endsection