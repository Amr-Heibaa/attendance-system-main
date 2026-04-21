@extends('layouts.admin')

@section('title', 'لوحة التحكم')
@section('page-title', 'لوحة التحكم')
@section('page-subtitle', 'ملخص الأداء والحضور والتنبيهات التشغيلية')

@section('content')
<div class="space-y-6">

    <!-- KPI Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-2xl p-5 shadow-sm border-r-4 border-green-400">
            <p class="text-gray-500 text-xs font-medium mb-1">عدد الحضور اليوم</p>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['present'] }}</p>
            <p class="text-green-500 text-xs mt-1">من {{ $stats['total'] }} موظف</p>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border-r-4 border-red-400">
            <p class="text-gray-500 text-xs font-medium mb-1">عدد الغياب اليوم</p>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['absent'] }}</p>
            <p class="text-red-500 text-xs mt-1">غائب</p>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border-r-4 border-yellow-400">
            <p class="text-gray-500 text-xs font-medium mb-1">عدد المتأخرين اليوم</p>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['late'] }}</p>
            <p class="text-yellow-500 text-xs mt-1">متأخر</p>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border-r-4 border-blue-400">
            <p class="text-gray-500 text-xs font-medium mb-1">متوسط ساعات العمل</p>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['avg_work_hours'] }}</p>
            <p class="text-blue-500 text-xs mt-1">ساعة</p>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border-r-4 border-purple-400">
            <p class="text-gray-500 text-xs font-medium mb-1">نسبة الالتزام</p>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['compliance_rate'] }}%</p>
            <p class="text-purple-500 text-xs mt-1">التزام</p>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold text-gray-700">توجه الحضور - آخر 30 يوم</h3>
                <span class="text-xs text-gray-400">تحليل زمني</span>
            </div>
            <canvas id="trendChart" height="200"></canvas>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold text-gray-700">مقارنة الأقسام (اليوم)</h3>
                <span class="text-xs text-gray-400">نسبة حضور</span>
            </div>
            <canvas id="deptChart" height="200"></canvas>
        </div>
    </div>

    <!-- Quick Actions -->
    <div>
        <h3 class="text-base font-semibold text-gray-700 mb-4">إجراءات سريعة</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.employees.create') }}" class="bg-white rounded-2xl p-5 shadow-sm hover:shadow-md transition flex flex-col items-center gap-2 text-center group">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center group-hover:bg-blue-200 transition">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">إضافة موظف</span>
            </a>

            <a href="{{ route('admin.notifications.index') }}" class="bg-white rounded-2xl p-5 shadow-sm hover:shadow-md transition flex flex-col items-center gap-2 text-center group">
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center group-hover:bg-yellow-200 transition">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">التنبيهات</span>
            </a>

            <a href="{{ route('admin.attendance.index') }}" class="bg-white rounded-2xl p-5 shadow-sm hover:shadow-md transition flex flex-col items-center gap-2 text-center group">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center group-hover:bg-green-200 transition">
                    <svg class="w-6 h-6 text-green-600 group-hover:scale-110 transition-transform duration-300"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <circle cx="12" cy="12" r="9" stroke-width="2" class="opacity-30" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M9 12l2 2 4-4" />
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">الحضور والانصراف</span>
            </a>

            <a href="{{ route('admin.reports.index') }}" class="bg-white rounded-2xl p-5 shadow-sm hover:shadow-md transition flex flex-col items-center gap-2 text-center group">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center group-hover:bg-purple-200 transition">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">مركز التقارير</span>
            </a>
        </div>
    </div>

    <!-- Alerts + Pending Requests -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Latest Alerts -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold text-gray-700">آخر التنبيهات</h3>
                <a href="{{ route('admin.notifications.index') }}" class="text-sm text-blue-600 hover:text-blue-800">عرض الكل</a>
            </div>

            <div class="space-y-3">
                @forelse(($latestAlerts ?? []) as $alert)
                <div class="flex items-start gap-3 p-4 rounded-xl border border-gray-100 hover:bg-gray-50 transition">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center
                            @if(($alert['type'] ?? '') === 'late') bg-yellow-100 text-yellow-600
                            @elseif(($alert['type'] ?? '') === 'absent') bg-red-100 text-red-600
                            @elseif(($alert['type'] ?? '') === 'leave') bg-blue-100 text-blue-600
                            @else bg-gray-100 text-gray-600
                            @endif">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>

                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800">{{ $alert['title'] ?? 'تنبيه' }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $alert['message'] ?? '-' }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-400 text-sm">
                    لا توجد تنبيهات حديثة
                </div>
                @endforelse
            </div>
        </div>

        <!-- Pending Requests -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold text-gray-700">آخر الطلبات المعلقة</h3>
                <a href="{{ route('admin.requests.index') }}" class="text-sm text-blue-600 hover:text-blue-800">عرض الكل</a>
            </div>

            <div class="space-y-3">
                @forelse(($pendingRequests ?? []) as $request)
                <div class="flex items-start gap-3 p-4 rounded-xl border border-gray-100 hover:bg-gray-50 transition">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-yellow-100 text-yellow-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>

                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800">{{ $request['employee_name'] ?? '-' }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $request['request_type'] ?? 'طلب' }} - {{ $request['date'] ?? '-' }}
                        </p>
                    </div>

                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                        معلق
                    </span>
                </div>
                @empty
                <div class="text-center py-8 text-gray-400 text-sm">
                    لا توجد طلبات معلقة
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
    const trendData = @json($trend ?? []);
    const trendLabels = trendData.map(d => d.day);
    const trendPresent = trendData.map(d => d.present_count);
    const trendLate = trendData.map(d => d.late_count);

    new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: {
            labels: trendLabels,
            datasets: [{
                    label: 'الحضور',
                    data: trendPresent,
                    borderColor: '#22c55e',
                    backgroundColor: 'rgba(34,197,94,0.1)',
                    tension: 0.4,
                    fill: true,
                },
                {
                    label: 'المتأخرون',
                    data: trendLate,
                    borderColor: '#eab308',
                    backgroundColor: 'rgba(234,179,8,0.1)',
                    tension: 0.4,
                    fill: true,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                }
            },
            scales: {
                x: {
                    ticks: {
                        maxTicksLimit: 10
                    }
                }
            }
        }
    });

    const deptData = @json($departmentStats ?? []);
    new Chart(document.getElementById('deptChart'), {
        type: 'bar',
        data: {
            labels: deptData.map(d => d.name),
            datasets: [{
                label: 'نسبة الحضور %',
                data: deptData.map(d => d.rate),
                backgroundColor: '#3b82f6',
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'y',
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: v => v + '%'
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection