@extends('layouts.admin')

@section('page-title', 'الموظفون')
@section('page-subtitle', 'إدارة بيانات الموظفين')

@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('admin.employees.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition">
            + إضافة موظف جديد
        </a>

        <!-- Search -->
        <form method="GET" class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث بالاسم أو كود الموظف..."
                class="border border-gray-300 rounded-xl px-4 py-2 text-sm w-56 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <select name="department_id" class="border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">كل الأقسام</option>
                @foreach($departments as $dept)
                <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name_ar }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-xl text-sm transition">بحث</button>
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600">
                    <th class="text-right py-3 px-4 font-semibold">الكود</th>
                    <th class="text-right py-3 px-4 font-semibold">الاسم</th>
                    <th class="text-right py-3 px-4 font-semibold">الموبايل</th>
                    <th class="text-right py-3 px-4 font-semibold">الرقم القومي</th>
                    <th class="text-right py-3 px-4 font-semibold">القسم</th>
                    <th class="text-right py-3 px-4 font-semibold">مكان العمل</th>
                    <th class="text-right py-3 px-4 font-semibold">الحالة</th>
                    <th class="text-right py-3 px-4 font-semibold">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($employees as $employee)
               <tr class="hover:bg-gray-50 transition">
    <td class="py-3 px-4 font-mono text-gray-500">{{ $employee->employee_code }}</td>

    <td class="py-3 px-4 font-medium text-gray-800">
        {{ $employee->user->name }}
    </td>

    <td class="py-3 px-4 text-gray-600">
        {{ $employee->phone ?? '-' }}
    </td>

    <td class="py-3 px-4 text-gray-600">
        {{ $employee->national_id ?? '-' }}
    </td>

    <td class="py-3 px-4 text-gray-600">
        {{ $employee->department?->name_ar ?? '-' }}
    </td>

    <td class="py-3 px-4 text-gray-600">
        {{ $employee->work_location ?? '-' }}
    </td>

    <td class="py-3 px-4">
        @php
            $statusClasses = [
                'active' => 'bg-green-100 text-green-700',
                'inactive' => 'bg-gray-100 text-gray-600',
                'suspended' => 'bg-red-100 text-red-700'
            ];
        @endphp

        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClasses[$employee->status] ?? '' }}">
            @if($employee->status === 'active') نشط
            @elseif($employee->status === 'inactive') غير نشط
            @else موقوف @endif
        </span>
    </td>

    <td class="py-3 px-4">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.employees.edit', $employee) }}"
               class="text-blue-600 hover:text-blue-800 text-xs font-medium">
               تعديل
            </a>

            <form method="POST"
                  action="{{ route('admin.employees.destroy', $employee) }}"
                  onsubmit="return confirm('هل أنت متأكد من حذف هذا الموظف؟')">
                @csrf
                @method('DELETE')

                <button type="submit"
                        class="text-red-500 hover:text-red-700 text-xs font-medium">
                    حذف
                </button>
            </form>
        </div>
    </td>
</tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-12 text-gray-400">لا توجد موظفون</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $employees->withQueryString()->links() }}
    </div>
</div>
@endsection