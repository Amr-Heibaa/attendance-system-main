@extends('layouts.admin')

@section('page-title', 'أنواع الإجازات')
@section('page-subtitle', 'إدارة أنواع الإجازات')

@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6">
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('admin.leave-types.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition">
            + إضافة نوع
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600">
                    <th class="text-right py-3 px-4 font-semibold">الاسم</th>
                    <th class="text-right py-3 px-4 font-semibold">الحد السنوي</th>
                    <th class="text-right py-3 px-4 font-semibold">مدفوعة</th>
                    <th class="text-right py-3 px-4 font-semibold">نشطة</th>
                    <th class="text-right py-3 px-4 font-semibold">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($leaveTypes as $t)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4 font-medium text-gray-800">{{ $t->name_ar }}</td>
                    <td class="py-3 px-4 text-gray-600">{{ $t->max_days_per_year }} يوم</td>
                    <td class="py-3 px-4 text-gray-600">{{ $t->is_paid ? 'نعم' : 'لا' }}</td>
                    <td class="py-3 px-4 text-gray-600">{{ $t->is_active ? 'نعم' : 'لا' }}</td>
                    <td class="py-3 px-4">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.leave-types.edit', $t) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium">تعديل</a>
                            <form method="POST" action="{{ route('admin.leave-types.destroy', $t) }}" onsubmit="return confirm('هل أنت متأكد؟')">
                                @csrf @method('DELETE')
                                <button class="text-red-500 hover:text-red-700 text-xs font-medium" type="submit">حذف</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-10 text-gray-400">لا توجد أنواع</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $leaveTypes->links() }}</div>
</div>
@endsection