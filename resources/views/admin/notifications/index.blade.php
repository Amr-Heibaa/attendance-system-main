@extends('layouts.admin')

@section('page-title', 'التنبيهات')
@section('page-subtitle', 'متابعة أهم الإشعارات والطلبات والحضور')

@section('content')
<div class="space-y-6">

    <!-- Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl shadow-sm p-5 border-r-4 border-yellow-400">
            <p class="text-sm text-gray-500 mb-1">طلبات الإجازات</p>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['pending_leaves'] }}</p>
            <p class="text-xs text-gray-400 mt-1">طلبات بانتظار المراجعة</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-5 border-r-4 border-blue-400">
            <p class="text-sm text-gray-500 mb-1">طلبات الأذون</p>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['pending_permissions'] }}</p>
            <p class="text-xs text-gray-400 mt-1">أذون جديدة تحتاج قرار</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-5 border-r-4 border-purple-400">
            <p class="text-sm text-gray-500 mb-1">طلبات المهمات</p>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['pending_missions'] }}</p>
            <p class="text-xs text-gray-400 mt-1">مهمات قيد المراجعة</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-5 border-r-4 border-green-400">
            <p class="text-sm text-gray-500 mb-1">طلبات بدل الراحة</p>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['pending_comp_off'] }}</p>
            <p class="text-xs text-gray-400 mt-1">طلبات تعويض بانتظار الاعتماد</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-5 border-r-4 border-indigo-400">
            <p class="text-sm text-gray-500 mb-1">طلبات تغيير الوردية</p>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['pending_shift_changes'] }}</p>
            <p class="text-xs text-gray-400 mt-1">طلبات تغيير وردية معلقة</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-5 border-r-4 border-orange-400">
            <p class="text-sm text-gray-500 mb-1">المتأخرون اليوم</p>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['late_today'] }}</p>
            <p class="text-xs text-gray-400 mt-1">سجلات حضور متأخرة</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-5 border-r-4 border-red-400">
            <p class="text-sm text-gray-500 mb-1">انصراف غير مكتمل</p>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['incomplete_today'] }}</p>
            <p class="text-xs text-gray-400 mt-1">موظفون لم يسجلوا انصرافًا</p>
        </div>
    </div>

    <!-- Pending Requests -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        <div class="bg-white rounded-2xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">أحدث طلبات الإجازة</h3>
                <a href="{{ route('admin.leave-requests.index', ['status' => 'pending']) }}" class="text-blue-600 text-sm hover:underline">عرض الكل</a>
            </div>
            <div class="space-y-3">
                @forelse($pendingLeaves as $item)
                    <div class="border border-gray-100 rounded-xl p-4 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-800">{{ $item->employee->user->name }}</p>
                            <p class="text-sm text-gray-500">إجازة من {{ $item->start_date->format('Y/m/d') }} إلى {{ $item->end_date->format('Y/m/d') }}</p>
                        </div>
                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs">معلقة</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">لا توجد طلبات إجازة معلقة</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">أحدث طلبات الأذون</h3>
                <a href="{{ route('admin.permission-requests.index', ['status' => 'pending']) }}" class="text-blue-600 text-sm hover:underline">عرض الكل</a>
            </div>
            <div class="space-y-3">
                @forelse($pendingPermissions as $item)
                    <div class="border border-gray-100 rounded-xl p-4 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-800">{{ $item->employee->user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $item->type_label }} - {{ $item->date->format('Y/m/d') }}</p>
                        </div>
                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs">معلقة</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">لا توجد طلبات أذون معلقة</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">أحدث طلبات المهمات</h3>
                <a href="{{ route('admin.mission-requests.index', ['status' => 'pending']) }}" class="text-blue-600 text-sm hover:underline">عرض الكل</a>
            </div>
            <div class="space-y-3">
                @forelse($pendingMissions as $item)
                    <div class="border border-gray-100 rounded-xl p-4 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-800">{{ $item->employee->user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $item->title }} - {{ $item->date->format('Y/m/d') }}</p>
                        </div>
                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs">معلقة</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">لا توجد طلبات مهمات معلقة</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">أحدث طلبات بدل الراحة</h3>
                <a href="{{ route('admin.comp-off-requests.index', ['status' => 'pending']) }}" class="text-blue-600 text-sm hover:underline">عرض الكل</a>
            </div>
            <div class="space-y-3">
                @forelse($pendingCompOff as $item)
                    <div class="border border-gray-100 rounded-xl p-4 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-800">{{ $item->employee->user->name }}</p>
                            <p class="text-sm text-gray-500">تعويض عن {{ $item->worked_on_date->format('Y/m/d') }}</p>
                        </div>
                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs">معلقة</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">لا توجد طلبات بدل راحة معلقة</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">طلبات تغيير الوردية</h3>
                <a href="{{ route('admin.shift-change-requests.index', ['status' => 'pending']) }}" class="text-blue-600 text-sm hover:underline">عرض الكل</a>
            </div>
            <div class="space-y-3">
                @forelse($pendingShiftChanges as $item)
                    <div class="border border-gray-100 rounded-xl p-4 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-800">{{ $item->employee->user->name }}</p>
                            <p class="text-sm text-gray-500">
                                {{ $item->currentShift?->name ?? '-' }} ← {{ $item->requestedShift?->name ?? '-' }}
                            </p>
                        </div>
                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs">معلقة</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">لا توجد طلبات تغيير وردية معلقة</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">تنبيهات الحضور اليوم</h3>

            <div class="space-y-3 mb-5">
                <h4 class="text-sm font-semibold text-orange-600">المتأخرون اليوم</h4>
                @forelse($lateToday as $item)
                    <div class="border border-gray-100 rounded-xl p-4">
                        <p class="font-medium text-gray-800">{{ $item->employee->user->name }}</p>
                        <p class="text-sm text-gray-500">تأخير {{ $item->late_minutes }} دقيقة</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">لا يوجد تأخير اليوم</p>
                @endforelse
            </div>

            <div class="space-y-3">
                <h4 class="text-sm font-semibold text-red-600">انصراف غير مكتمل</h4>
                @forelse($incompleteToday as $item)
                    <div class="border border-gray-100 rounded-xl p-4">
                        <p class="font-medium text-gray-800">{{ $item->employee->user->name }}</p>
                        <p class="text-sm text-gray-500">تم تسجيل حضور بدون انصراف</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">لا توجد سجلات ناقصة اليوم</p>
                @endforelse
            </div>
        </div>



        
    </div>
        <!-- Real Notifications Log -->
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">سجل التنبيهات</h3>
                <p class="text-sm text-gray-500 mt-1">التنبيهات المحفوظة فعليًا داخل النظام</p>
            </div>

            <form method="POST" action="{{ route('admin.notifications.read-all') }}">
                @csrf
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition">
                    تحديد الكل كمقروء
                </button>
            </form>
        </div>

        <div class="space-y-3">
            @forelse($notifications as $notification)
                <div class="rounded-2xl border {{ $notification->is_read ? 'border-gray-100 bg-gray-50' : 'border-blue-100 bg-blue-50' }} p-4">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <h4 class="font-semibold text-gray-800">{{ $notification->title }}</h4>

                                @if(!$notification->is_read)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                        جديد
                                    </span>
                                @endif
                            </div>

                            <p class="text-sm text-gray-600">{{ $notification->message ?? '-' }}</p>

                            <div class="mt-2 text-xs text-gray-400">
                                {{ $notification->created_at->format('Y/m/d H:i') }}
                            </div>
                        </div>

                        @if(!$notification->is_read)
                            <form method="POST" action="{{ route('admin.notifications.read', $notification) }}">
                                @csrf
                                <button type="submit"
                                    class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                    تمت القراءة
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-400 text-sm">
                    لا توجد تنبيهات محفوظة
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    </div>
</div>



@endsection