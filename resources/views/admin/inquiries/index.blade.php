@extends('layouts.admin')

@section('page-title', 'الاستعلامات')
@section('page-subtitle', 'استعلامات وتقارير سريعة حسب النوع والفترة')

@section('content')
<div class="space-y-6">

    <!-- Search Form -->
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm text-gray-600 mb-1">نوع الاستعلام</label>
                <select name="type" class="w-full border border-gray-300 rounded-xl px-4 py-2.5">
                    <option value="leaves" {{ $type === 'leaves' ? 'selected' : '' }}>الإجازات</option>
                    <option value="missions" {{ $type === 'missions' ? 'selected' : '' }}>المهمات</option>
                    <option value="permissions" {{ $type === 'permissions' ? 'selected' : '' }}>الأذون</option>
                    <option value="late" {{ $type === 'late' ? 'selected' : '' }}>التأخير</option>
                </select>
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">الموظف</label>
                <select name="employee_id" class="w-full border border-gray-300 rounded-xl px-4 py-2.5">
                    <option value="">كل الموظفين</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ (string)$employeeId === (string)$emp->id ? 'selected' : '' }}>
                            {{ $emp->user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">من تاريخ</label>
                <input type="date" name="from" value="{{ $from }}" class="w-full border border-gray-300 rounded-xl px-4 py-2.5">
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">إلى تاريخ</label>
                <input type="date" name="to" value="{{ $to }}" class="w-full border border-gray-300 rounded-xl px-4 py-2.5">
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-xl font-medium transition">
                    بحث
                </button>
            </div>
        </form>
    </div>

    <!-- Results -->
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">
            نتائج الاستعلام
        </h3>

        @if($type === 'leaves')
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600">
                            <th class="text-right py-3 px-4">الموظف</th>
                            <th class="text-right py-3 px-4">نوع الإجازة</th>
                            <th class="text-right py-3 px-4">من</th>
                            <th class="text-right py-3 px-4">إلى</th>
                            <th class="text-right py-3 px-4">الحالة</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($results as $row)
                            <tr>
                                <td class="py-3 px-4">{{ $row->employee->user->name }}</td>
                                <td class="py-3 px-4">{{ $row->leaveType->name_ar ?? '-' }}</td>
                                <td class="py-3 px-4">{{ $row->start_date->format('Y/m/d') }}</td>
                                <td class="py-3 px-4">{{ $row->end_date->format('Y/m/d') }}</td>
                                <td class="py-3 px-4">{{ $row->status_label }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-8 text-gray-400">لا توجد نتائج</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif

        @if($type === 'missions')
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600">
                            <th class="text-right py-3 px-4">الموظف</th>
                            <th class="text-right py-3 px-4">العنوان</th>
                            <th class="text-right py-3 px-4">النوع</th>
                            <th class="text-right py-3 px-4">التاريخ</th>
                            <th class="text-right py-3 px-4">الحالة</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($results as $row)
                            <tr>
                                <td class="py-3 px-4">{{ $row->employee->user->name }}</td>
                                <td class="py-3 px-4">{{ $row->title }}</td>
                                <td class="py-3 px-4">{{ $row->type_label }}</td>
                                <td class="py-3 px-4">{{ $row->date->format('Y/m/d') }}</td>
                                <td class="py-3 px-4">{{ $row->status_label }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-8 text-gray-400">لا توجد نتائج</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif

        @if($type === 'permissions')
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600">
                            <th class="text-right py-3 px-4">الموظف</th>
                            <th class="text-right py-3 px-4">النوع</th>
                            <th class="text-right py-3 px-4">التاريخ</th>
                            <th class="text-right py-3 px-4">المدة</th>
                            <th class="text-right py-3 px-4">الحالة</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($results as $row)
                            <tr>
                                <td class="py-3 px-4">{{ $row->employee->user->name }}</td>
                                <td class="py-3 px-4">{{ $row->type_label }}</td>
                                <td class="py-3 px-4">{{ $row->date->format('Y/m/d') }}</td>
                                <td class="py-3 px-4">{{ $row->minutes_count }} دقيقة</td>
                                <td class="py-3 px-4">{{ $row->status_label }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-8 text-gray-400">لا توجد نتائج</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif

        @if($type === 'late')
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600">
                            <th class="text-right py-3 px-4">الموظف</th>
                            <th class="text-right py-3 px-4">التاريخ</th>
                            <th class="text-right py-3 px-4">وقت الحضور</th>
                            <th class="text-right py-3 px-4">دقائق التأخير</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($results as $row)
                            <tr>
                                <td class="py-3 px-4">{{ $row->employee->user->name }}</td>
                                <td class="py-3 px-4">{{ $row->date->format('Y/m/d') }}</td>
                                <td class="py-3 px-4">{{ $row->check_in?->format('H:i') ?? '-' }}</td>
                                <td class="py-3 px-4">{{ $row->late_minutes }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center py-8 text-gray-400">لا توجد نتائج</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection