@extends('layouts.admin')

@section('page-title', 'الطلبات')
@section('page-subtitle', 'إدارة جميع الطلبات المقدمة من الموظفين')

@section('content')
<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        <a href="{{ route('admin.leave-requests.index') }}" class="bg-white rounded-2xl shadow-sm p-5 hover:shadow-md transition border border-gray-100">
            <h3 class="font-semibold text-gray-800 mb-2">الإجازات</h3>
            <p class="text-sm text-gray-500">طلبات الإجازات السنوية والمرضية وغيرها.</p>
        </a>

        <a href="{{ route('admin.permission-requests.index') }}" class="bg-white rounded-2xl shadow-sm p-5 hover:shadow-md transition border border-gray-100">
            <h3 class="font-semibold text-gray-800 mb-2">الأذون</h3>
            <p class="text-sm text-gray-500">طلبات إذن الخروج أو التأخير أو الانصراف المبكر.</p>
        </a>

        <a href="{{ route('admin.mission-requests.index') }}" class="bg-white rounded-2xl shadow-sm p-5 hover:shadow-md transition border border-gray-100">
            <h3 class="font-semibold text-gray-800 mb-2">المهمات</h3>
            <p class="text-sm text-gray-500">طلبات المهمات الداخلية أو الخارجية للموظفين.</p>
        </a>

        <a href="{{ route('admin.comp-off-requests.index') }}" class="bg-white rounded-2xl shadow-sm p-5 hover:shadow-md transition border border-gray-100">
            <h3 class="font-semibold text-gray-800 mb-2">إذن بدل الراحة</h3>
            <p class="text-sm text-gray-500">طلبات التعويض عن العمل في أيام الراحة.</p>
        </a>

        <a href="{{ route('admin.shift-change-requests.index') }}" class="bg-white rounded-2xl shadow-sm p-5 hover:shadow-md transition border border-gray-100">
            <h3 class="font-semibold text-gray-800 mb-2">تغيير وردية</h3>
            <p class="text-sm text-gray-500">طلبات تعديل أو تبديل الوردية للموظف.</p>
        </a>

        <a href="#" class="bg-white rounded-2xl shadow-sm p-5 hover:shadow-md transition border border-gray-100">
            <h3 class="font-semibold text-gray-800 mb-2">تسجيل بصمة</h3>
            <p class="text-sm text-gray-500">سيتم الربط لاحقًا مع جهاز البصمة المعتمد لدى الشركة.</p>
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">ملخص الطلبات</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-600">
                        <th class="text-right py-3 px-4 font-semibold">نوع الطلب</th>
                        <th class="text-right py-3 px-4 font-semibold">إجمالي الطلبات</th>
                        <th class="text-right py-3 px-4 font-semibold">المعلقة</th>
                        <th class="text-right py-3 px-4 font-semibold">الحالة</th>
                        <th class="text-right py-3 px-4 font-semibold">الانتقال</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($summary as $item)
                        <tr>
                            <td class="py-3 px-4 text-gray-800 font-medium">{{ $item['name'] }}</td>
                            <td class="py-3 px-4 text-gray-600">{{ $item['count'] }}</td>
                            <td class="py-3 px-4">
                                @if($item['pending_count'] > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                        {{ $item['pending_count'] }}
                                    </span>
                                @else
                                    <span class="text-gray-500">0</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                    {{ $item['status'] }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <a href="{{ $item['route'] }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    فتح
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-12 text-gray-400">لا توجد بيانات طلبات</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection