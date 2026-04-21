@extends('layouts.admin')

@section('page-title', 'الشرائح المالية')
@section('page-subtitle', 'إدارة سياسات الخصم والجزاءات')

@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6">
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('admin.financial-policies.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition">
            + إضافة شريحة مالية
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600">
                    <th class="text-right py-3 px-4 font-semibold">الاسم</th>
                    <th class="text-right py-3 px-4 font-semibold">النوع</th>
                    <th class="text-right py-3 px-4 font-semibold">من</th>
                    <th class="text-right py-3 px-4 font-semibold">إلى</th>
                    <th class="text-right py-3 px-4 font-semibold">نوع الجزاء</th>
                    <th class="text-right py-3 px-4 font-semibold">القيمة</th>
                    <th class="text-right py-3 px-4 font-semibold">الحالة</th>
                    <th class="text-right py-3 px-4 font-semibold">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($policies as $policy)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4">{{ $policy->name }}</td>
                        <td class="py-3 px-4">
                            @if($policy->type === 'late') تأخير
                            @elseif($policy->type === 'absence') غياب
                            @else خروج مبكر
                            @endif
                        </td>
                        <td class="py-3 px-4">{{ $policy->minutes_from ?? '-' }}</td>
                        <td class="py-3 px-4">{{ $policy->minutes_to ?? '-' }}</td>
                        <td class="py-3 px-4">
                            @if($policy->penalty_type === 'fixed') مبلغ ثابت
                            @elseif($policy->penalty_type === 'percent') نسبة
                            @else إنذار
                            @endif
                        </td>
                        <td class="py-3 px-4">{{ $policy->penalty_value ?? '-' }}</td>
                        <td class="py-3 px-4">
                            @if($policy->is_active)
                                <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">نشطة</span>
                            @else
                                <span class="px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-600">غير نشطة</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.financial-policies.edit', $policy) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium">تعديل</a>
                                <form method="POST" action="{{ route('admin.financial-policies.destroy', $policy) }}" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium">حذف</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-12 text-gray-400">لا توجد شرائح مالية</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $policies->links() }}
    </div>
</div>
@endsection