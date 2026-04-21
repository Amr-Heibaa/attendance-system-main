<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'بوابة الموظف') - نظام الحضور</title>

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        * {
            font-family: 'Cairo', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen">

    <!-- Navbar -->
    <nav class="bg-blue-700 text-white shadow-lg">
        <div class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between">

            <h1 class="text-lg font-bold">نظام الحضور والغياب</h1>

            <div class="flex items-center gap-4">

                <a href="{{ route('employee.dashboard') }}"
                    class="text-blue-100 hover:text-white text-sm {{ request()->routeIs('employee.dashboard') ? 'text-white font-semibold' : '' }}">
                    الرئيسية
                </a>

                <a href="{{ route('employee.attendance.index') }}"
                    class="text-blue-100 hover:text-white text-sm {{ request()->routeIs('employee.attendance*') ? 'text-white font-semibold' : '' }}">
                    سجل الحضور
                </a>

                <a href="{{ route('employee.leaves.index') }}"
                    class="text-blue-100 hover:text-white text-sm {{ request()->routeIs('employee.leaves*') ? 'text-white font-semibold' : '' }}">
                    الإجازات
                </a>

                <a href="{{ route('employee.permissions.index') }}"
                    class="text-blue-100 hover:text-white text-sm {{ request()->routeIs('employee.permissions*') ? 'text-white font-semibold' : '' }}">
                    الأذون
                </a>
                <a href="{{ route('employee.comp-off.index') }}"
                    class="text-blue-100 hover:text-white text-sm {{ request()->routeIs('employee.comp-off*') ? 'text-white font-semibold' : '' }}">
                    بدل الراحة
                </a>

                <a href="{{ route('employee.missions.index') }}"
                    class="text-blue-100 hover:text-white text-sm {{ request()->routeIs('employee.missions*') ? 'text-white font-semibold' : '' }}">
                    المهمات
                </a>

                <a href="{{ route('employee.shift-changes.index') }}"
                    class="text-blue-100 hover:text-white text-sm {{ request()->routeIs('employee.shift-changes*') ? 'text-white font-semibold' : '' }}">
                    تغيير الوردية
                </a>

                @if(auth()->user()->hasAnyRole(['admin', 'manager']))
                <a href="{{ route('admin.dashboard') }}"
                    class="bg-blue-500 hover:bg-blue-400 px-3 py-1 rounded text-sm">
                    لوحة الإدارة
                </a>
                @endif

                <span class="text-blue-200 text-sm">
                    | {{ auth()->user()->name }}
                </span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-blue-100 hover:text-white text-sm">
                        خروج
                    </button>
                </form>

            </div>
        </div>
    </nav>

    <!-- Content -->
    <main class="max-w-5xl mx-auto px-4 py-6">

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-4">
            ✓ {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-4">
            ✗ {{ session('error') }}
        </div>
        @endif

        @yield('content')

    </main>

</body>

</html>