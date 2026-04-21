@extends('layouts.admin')

@section('page-title', 'الورديات')
@section('page-subtitle', 'إدارة الورديات')

@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6">
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('admin.shifts.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition">
            + إضافة وردية
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600">
                    <th class="text-right py-3 px-4 font-semibold">الاسم</th>
                    <th class="text-right py-3 px-4 font-semibold">البدء</th>
                    <th class="text-right py-3 px-4 font-semibold">الانتهاء</th>
                    <th class="text-right py-3 px-4 font-semibold">السماح</th>
                    <th class="text-right py-3 px-4 font-semibold">عدد الموظفين</th>
                    <th class="text-right py-3 px-4 font-semibold">الحالة</th>
                    <th class="text-right py-3 px-4 font-semibold">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($shifts as $shift)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4 font-medium text-gray-800">{{ $shift->name }}</td>
                    <td class="py-3 px-4 text-gray-600">{{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}</td>
                    <td class="py-3 px-4 text-gray-600">{{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}</td>
                    <td class="py-3 px-4 text-gray-600">{{ $shift->grace_minutes }} دقيقة</td>
                    <td class="py-3 px-4 text-gray-600">{{ $shift->employees_count }}</td>
                    <td class="py-3 px-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $shift->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $shift->is_active ? 'نشط' : 'غير نشط' }}
                        </span>
                    </td>
                    <td class="py-3 px-4">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.shifts.edit', $shift) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium">تعديل</a>
                            <form method="POST" action="{{ route('admin.shifts.destroy', $shift) }}" onsubmit="return confirm('هل أنت متأكد؟')">
                                @csrf @method('DELETE')
                                <button class="text-red-500 hover:text-red-700 text-xs font-medium" type="submit">حذف</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-10 text-gray-400">لا توجد ورديات</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $shifts->links() }}</div>
</div>
@endsection