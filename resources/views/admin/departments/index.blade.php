@extends('layouts.admin')

@section('page-title', 'الأقسام')
@section('page-subtitle', 'إدارة الأقسام')

@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
        <div class="flex gap-3">
            <a href="{{ route('admin.departments.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition">
                + إضافة قسم
            </a>

            <a href="{{ route('admin.departments.import.form') }}"
               class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition">
                استيراد Excel
            </a>
        </div>

        <a href="{{ route('admin.departments.template.excel') }}"
           class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-xl text-sm font-medium transition">
            تحميل نموذج Excel
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600">
                    <th class="text-right py-3 px-4 font-semibold">الاسم</th>
                    <th class="text-right py-3 px-4 font-semibold">الوصف</th>
                    <th class="text-right py-3 px-4 font-semibold">عدد الموظفين</th>
                    <th class="text-right py-3 px-4 font-semibold">الحالة</th>
                    <th class="text-right py-3 px-4 font-semibold">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($departments as $dept)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4 font-medium text-gray-800">{{ $dept->name_ar ?? $dept->name }}</td>
                    <td class="py-3 px-4 text-gray-600">{{ $dept->description ?? '-' }}</td>
                    <td class="py-3 px-4 text-gray-600">{{ $dept->employees_count }}</td>
                    <td class="py-3 px-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $dept->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $dept->is_active ? 'نشط' : 'غير نشط' }}
                        </span>
                    </td>
                    <td class="py-3 px-4">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.departments.edit', $dept) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium">تعديل</a>
                            <form method="POST" action="{{ route('admin.departments.destroy', $dept) }}" onsubmit="return confirm('هل أنت متأكد؟')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-500 hover:text-red-700 text-xs font-medium" type="submit">حذف</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-10 text-gray-400">لا توجد أقسام</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $departments->links() }}</div>
</div>
@endsection