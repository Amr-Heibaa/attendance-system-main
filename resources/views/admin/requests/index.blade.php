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
                        <th class="text-right py-3 px-4 font-semibold">عدد الطلبات</th>
                        <th class="text-right py-3 px-4 font-semibold">الحالة</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td class="py-3 px-4 text-gray-800">الإجازات</td>
                        <td class="py-3 px-4 text-gray-600">0</td>
                        <td class="py-3 px-4 text-gray-500">غير مفعلة بعد</td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 text-gray-800">الأذون</td>
                        <td class="py-3 px-4 text-gray-600">0</td>
                        <td class="py-3 px-4 text-gray-500">غير مفعلة بعد</td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 text-gray-800">المهمات</td>
                        <td class="py-3 px-4 text-gray-600">0</td>
                        <td class="py-3 px-4 text-gray-500">غير مفعلة بعد</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection