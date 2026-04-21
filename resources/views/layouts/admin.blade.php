<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'لوحة التحكم') - نظام الحضور</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        * { font-family: 'Cairo', sans-serif; }
        body { background-color: #f1f5f9; }
        .sidebar-link:hover { background-color: #1e3a5f; }
        .sidebar-link.active { background-color: #2563eb; }
        .sidebar-section-title {
            color: #94a3b8;
            font-size: 11px;
            padding: 0 16px;
            margin-bottom: 8px;
            margin-top: 10px;
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-72 min-h-screen bg-gray-900 text-white flex flex-col fixed right-0 top-0 z-50 overflow-y-auto">
        <div class="p-6 border-b border-gray-700">
           <div class="flex flex-col items-center gap-2">
    <img src="{{ asset('images/logo.png') }}"
         alt="Company Logo"
         class="h-12 w-auto object-contain">

    <h1 class="text-sm font-semibold text-gray-200 text-center">
        نظام الحضور والغياب
    </h1>

    <p class="text-gray-400 text-xs text-center">
        لوحة الإدارة
    </p>
</div>
        </div>

        <nav class="flex-1 p-4 space-y-1">

            <!-- الرئيسية -->
            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:text-white transition {{ request()->routeIs('admin.dashboard') ? 'active text-white' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/>
                </svg>
                لوحة التحكم
            </a>

            <!-- الأقسام الجديدة -->
            <div class="border-t border-gray-700 pt-3 mt-3">
                <p class="sidebar-section-title">الأقسام الرئيسية</p>

                <a href="{{ route('admin.notifications.index') }}"
                   class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:text-white transition {{ request()->routeIs('admin.notifications*') ? 'active text-white' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0m6 0H9"/>
                    </svg>
                    التنبيهات
                </a>

                <a href="{{ route('admin.requests.index') }}"
                   class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:text-white transition {{ request()->routeIs('admin.requests*') ? 'active text-white' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    الطلبات
                </a>

                <a href="{{ route('admin.inquiries.index') }}"
                   class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:text-white transition {{ request()->routeIs('admin.inquiries*') ? 'active text-white' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16h8M8 12h8m-8-4h5m6 11H5a2 2 0 01-2-2V7a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2z"/>
                    </svg>
                    الاستعلامات
                </a>

                <a href="{{ route('admin.work-system.index') }}"
                   class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:text-white transition {{ request()->routeIs('admin.work-system*') ? 'active text-white' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 3a.75.75 0 01.75.75V5h3V3.75a.75.75 0 011.5 0V5H18a2 2 0 012 2v10a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h3.75V3.75A.75.75 0 019.75 3zM6 9v8h12V9H6z"/>
                    </svg>
                    نظام العمل
                </a>

                <a href="{{ route('admin.reports.index') }}"
                   class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:text-white transition {{ request()->routeIs('admin.reports.index') ? 'active text-white' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    مركز التقارير
                </a>
            </div>

            <!-- الوظائف الحالية
            <div class="border-t border-gray-700 pt-3 mt-3">
                <p class="sidebar-section-title">الوظائف الحالية</p>

                <a href="{{ route('admin.employees.index') }}"
                   class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:text-white transition {{ request()->routeIs('admin.employees*') ? 'active text-white' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
                    </svg>
                    إدارة الموظفين
                </a>

                <a href="{{ route('admin.departments.index') }}"
                   class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:text-white transition {{ request()->routeIs('admin.departments*') ? 'active text-white' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                    </svg>
                    الأقسام
                </a>

                <a href="{{ route('admin.shifts.index') }}"
                   class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:text-white transition {{ request()->routeIs('admin.shifts*') ? 'active text-white' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    الورديات
                </a>

                <a href="{{ route('admin.holidays.index') }}"
                   class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:text-white transition {{ request()->routeIs('admin.holidays*') ? 'active text-white' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    العطل الرسمية
                </a>

                <a href="{{ route('admin.leave-types.index') }}"
                   class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:text-white transition {{ request()->routeIs('admin.leave-types*') ? 'active text-white' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                    </svg>
                    أنواع الإجازات
                </a>

                <a href="{{ route('admin.leave-requests.index') }}"
                   class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:text-white transition {{ request()->routeIs('admin.leave-requests*') ? 'active text-white' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    طلبات الإجازة
                </a>
            </div> -->

            <!-- التقارير الحالية
            <div class="border-t border-gray-700 pt-3 mt-3">
                <p class="sidebar-section-title">التقارير الحالية</p>

                <a href="{{ route('admin.reports.monthly') }}"
                   class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:text-white transition {{ request()->routeIs('admin.reports.monthly') ? 'active text-white' : '' }}">
                    تقرير شهري للموظف
                </a>

                <a href="{{ route('admin.reports.late') }}"
                   class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:text-white transition {{ request()->routeIs('admin.reports.late') ? 'active text-white' : '' }}">
                    تقرير المتأخرين
                </a>

                <a href="{{ route('admin.reports.department') }}"
                   class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:text-white transition {{ request()->routeIs('admin.reports.department') ? 'active text-white' : '' }}">
                    تقرير القسم
                </a>
            </div> -->
        </nav>

        <div class="p-4 border-t border-gray-700">
            <p class="text-gray-400 text-sm mb-2">{{ auth()->user()->name }}</p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-center text-red-400 hover:text-red-300 text-sm py-2 rounded hover:bg-gray-800 transition">
                    تسجيل الخروج
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 mr-72 min-h-screen">
        <!-- Top Bar -->
        <header class="bg-white shadow-sm px-6 py-4 flex items-center justify-between relative">
    <div>
        <h2 class="text-xl font-semibold text-gray-800">@yield('page-title', 'لوحة التحكم')</h2>
        <p class="text-gray-500 text-sm mt-0.5">@yield('page-subtitle', '')</p>
    </div>

    <div class="flex items-center gap-4">
        <div class="text-gray-600 text-sm hidden md:block">
            {{ now()->locale('ar')->isoFormat('dddd، D MMMM YYYY') }}
        </div>

        <!-- Notifications Dropdown -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open"
                class="relative bg-gray-100 hover:bg-gray-200 transition rounded-xl p-3">
                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>

                @if(($unreadNotificationsCount ?? 0) > 0)
                    <span class="absolute -top-1 -left-1 bg-red-500 text-white text-[10px] min-w-[18px] h-[18px] px-1 rounded-full flex items-center justify-center">
                        {{ $unreadNotificationsCount > 99 ? '99+' : $unreadNotificationsCount }}
                    </span>
                @endif
            </button>

            <div x-show="open" @click.away="open = false" x-transition
                class="absolute left-0 mt-3 w-96 bg-white border border-gray-100 rounded-2xl shadow-lg z-50 overflow-hidden">
                <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-800">آخر التنبيهات</h3>
                    <a href="{{ route('admin.notifications.index') }}"
                        class="text-sm text-blue-600 hover:text-blue-800">عرض الكل</a>
                </div>

                <div class="max-h-96 overflow-y-auto">
                    @forelse(($latestNavbarNotifications ?? []) as $notification)
                        <div class="p-4 border-b border-gray-50 hover:bg-gray-50 transition">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-800">{{ $notification->title }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $notification->message ?? '-' }}</p>
                                    <p class="text-[11px] text-gray-400 mt-2">{{ $notification->created_at->format('Y/m/d H:i') }}</p>
                                </div>

                                @if(!$notification->is_read)
                                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-red-500 mt-1"></span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-sm text-gray-400">
                            لا توجد تنبيهات
                        </div>
                    @endforelse
                </div>

                <div class="p-3 bg-gray-50 border-t border-gray-100">
                    <form method="POST" action="{{ route('admin.notifications.read-all') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-center text-sm text-blue-600 hover:text-blue-800 font-medium">
                            تحديد الكل كمقروء
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

        <!-- Flash Messages -->
        <div class="px-6 pt-4">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif
        </div>

        <div class="p-6">
            @yield('content')
        </div>
    </main>

    @stack('scripts')
</body>
</html>