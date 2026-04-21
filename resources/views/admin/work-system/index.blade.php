@extends('layouts.admin')

@section('page-title', 'نظام العمل')
@section('page-subtitle', 'إدارة بيانات وإعدادات التشغيل واللوائح')

@section('content')
<div class="space-y-6">

    <!-- قسم الوحدات الفعلية -->
    <div>
        <h3 class="text-lg font-bold text-gray-800 mb-4">الوحدات المتاحة</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">

            <a href="{{ route('admin.employees.index') }}"
                class="bg-white rounded-2xl shadow-sm p-5 hover:shadow-md transition border border-gray-100">
                <h4 class="font-semibold text-gray-800 mb-2">إدارة الموظفين</h4>
                <p class="text-sm text-gray-500">إضافة وتعديل وحذف ومراجعة بيانات الموظفين.</p>
            </a>

            <a href="{{ route('admin.shifts.index') }}"
                class="bg-white rounded-2xl shadow-sm p-5 hover:shadow-md transition border border-gray-100">
                <h4 class="font-semibold text-gray-800 mb-2">الورديات</h4>
                <p class="text-sm text-gray-500">إعداد الورديات وربطها بالموظفين.</p>
            </a>

            <a href="{{ route('admin.holidays.index') }}"
                class="bg-white rounded-2xl shadow-sm p-5 hover:shadow-md transition border border-gray-100">
                <h4 class="font-semibold text-gray-800 mb-2">الإجازات الرسمية</h4>
                <p class="text-sm text-gray-500">إدارة العطلات الرسمية وأيام الإجازات العامة.</p>
            </a>

            <a href="{{ route('admin.leave-types.index') }}"
                class="bg-white rounded-2xl shadow-sm p-5 hover:shadow-md transition border border-gray-100">
                <h4 class="font-semibold text-gray-800 mb-2">الإجازات الخاصة</h4>
                <p class="text-sm text-gray-500">إدارة أنواع الإجازات المختلفة وسياساتها.</p>
            </a>

            <a href="{{ route('admin.requests.index') }}"
                class="bg-white rounded-2xl shadow-sm p-5 hover:shadow-md transition border border-gray-100">
                <h4 class="font-semibold text-gray-800 mb-2">تعديل الطلبات</h4>
                <p class="text-sm text-gray-500">الانتقال إلى مركز الطلبات لمراجعة جميع الطلبات واعتمادها.</p>
            </a>

            <a href="{{route('admin.attendance.index') }}"
                class="bg-white rounded-2xl shadow-sm p-5 hover:shadow-md transition border border-gray-100">
                <h4 class="font-semibold text-gray-800 mb-2">الحضور والانصراف</h4>
                <p class="text-sm text-gray-500">متابعة المتأخرين وسجلات الحضور والانصراف اليومية.</p>
            </a>

            <a href="{{ route('admin.mission-requests.index') }}"
                class="bg-white rounded-2xl shadow-sm p-5 hover:shadow-md transition border border-gray-100">
                <h4 class="font-semibold text-gray-800 mb-2">إدارة المهمات</h4>
                <p class="text-sm text-gray-500">مراجعة واعتماد أو رفض طلبات المهمات.</p>
            </a>


            <a href="{{ route('admin.leave-balances.index') }}"
                class="bg-white rounded-2xl shadow-sm p-5 hover:shadow-md transition border border-gray-100">
                <h4 class="font-semibold text-gray-800 mb-2">رصيد الإجازات</h4>
                <p class="text-sm text-gray-500">عرض رصيد الإجازات لكل موظف.</p>
            </a>

            <a href="{{ route('admin.audit-logs.index') }}" class="bg-white rounded-2xl shadow-sm p-5 hover:shadow-md transition border border-gray-100">
                <h3 class="font-semibold text-gray-800 mb-2">تقرير التعديلات</h3>
                <p class="text-sm text-gray-500">سجل كامل بالإجراءات والاعتمادات والرفض داخل النظام.</p>
            </a>


            <a href="{{ route('admin.work-schedules.index') }}"
                class="bg-white rounded-2xl shadow-sm p-5 hover:shadow-md transition border border-gray-100">
                <h4 class="font-semibold text-gray-800 mb-2">المواعيد المنتظمة</h4>
                <p class="text-sm text-gray-500">إدارة سياسات الدوام العامة والفترات المؤقتة والدائمة.</p>
            </a>

            <a href="{{ route('admin.departments.index') }}"
                class="bg-white rounded-2xl shadow-sm p-5 hover:shadow-md transition border border-gray-100">
                <h4 class="font-semibold text-gray-800 mb-2">إدارة الأقسام</h4>
                <p class="text-sm text-gray-500">إضافة وتعديل وحذف الأقسام وربطها بالموظفين.</p>
            </a>



            <a href="{{ route('admin.financial-policies.index') }}"
                class="bg-white rounded-2xl shadow-sm p-5 hover:shadow-md transition border border-gray-100">
                <h4 class="font-semibold text-gray-800 mb-2">الشرائح المالية</h4>
                <p class="text-sm text-gray-500">إدارة شرائح الخصومات والجزاءات المرتبطة بالتأخير والغياب والخروج المبكر.</p>
            </a>
        </div>
    </div>


</div>
@endsection