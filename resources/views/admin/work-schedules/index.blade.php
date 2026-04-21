@extends('layouts.admin')

@section('page-title', 'المواعيد المنتظمة')
@section('page-subtitle', 'إدارة سياسات ومواعيد العمل العامة')

@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6">
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('admin.work-schedules.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition">
            + إضافة موعد منتظم
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600">
                    <th class="text-right py-3 px-4 font-semibold">الاسم</th>
                    <th class="text-right py-3 px-4 font-semibold">من</th>
                    <th class="text-right py-3 px-4 font-semibold">إلى</th>
                    <th class="text-right py-3 px-4 font-semibold">السماح</th>
                    <th class="text-right py-3 px-4 font-semibold">فترة السريان</th>
                    <th class="text-right py-3 px-4 font-semibold">الحالة</th>
                    <th class="text-right py-3 px-4 font-semibold">افتراضي</th>
                    <th class="text-right py-3 px-4 font-semibold">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($schedules as $schedule)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4">{{ $schedule->name }}</td>
                        <td class="py-3 px-4">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}</td>
                        <td class="py-3 px-4">{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                        <td class="py-3 px-4">{{ $schedule->grace_minutes }} دقيقة</td>
                        <td class="py-3 px-4">
                            {{ $schedule->effective_from->format('Y/m/d') }}
                            -
                            {{ $schedule->effective_to?->format('Y/m/d') ?? 'مفتوح' }}
                        </td>
                        <td class="py-3 px-4">
                            @if($schedule->is_active)
                                <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">نشط</span>
                            @else
                                <span class="px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-600">غير نشط</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            @if($schedule->is_default)
                                <span class="px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-700">افتراضي</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.work-schedules.edit', $schedule) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium">تعديل</a>
                                <form method="POST" action="{{ route('admin.work-schedules.destroy', $schedule) }}" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium">حذف</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-12 text-gray-400">لا توجد مواعيد منتظمة</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $schedules->links() }}
    </div>
</div>
@endsection