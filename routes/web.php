<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Employee;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => redirect()->route('login'));

// Auth (Breeze)
require __DIR__ . '/auth.php';


/*
|--------------------------------------------------------------------------
| Employee Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:employee,manager,admin'])
    ->prefix('employee')
    ->name('employee.')
    ->group(function () {

        Route::get('/dashboard', [Employee\DashboardController::class, 'index'])->name('dashboard');

        Route::post('/check-in', [Employee\DashboardController::class, 'checkIn'])->name('check-in');
        Route::post('/check-out', [Employee\DashboardController::class, 'checkOut'])->name('check-out');

        Route::get('/attendance', [Employee\AttendanceController::class, 'index'])->name('attendance.index');

        Route::get('/leaves', [Employee\LeaveController::class, 'index'])->name('leaves.index');
        Route::get('/leaves/create', [Employee\LeaveController::class, 'create'])->name('leaves.create');
        Route::post('/leaves', [Employee\LeaveController::class, 'store'])->name('leaves.store');

        /*
    |--------------------------------------------------------------------------
    | Permission Requests
    |--------------------------------------------------------------------------
    */
        Route::get('/permissions', [Employee\PermissionController::class, 'index'])->name('permissions.index');
        Route::get('/permissions/create', [Employee\PermissionController::class, 'create'])->name('permissions.create');
        Route::post('/permissions', [Employee\PermissionController::class, 'store'])->name('permissions.store');

        /*
    |--------------------------------------------------------------------------
    | CompOffRequests
    |--------------------------------------------------------------------------
    */

        Route::get('/comp-off', [Employee\CompOffController::class, 'index'])->name('comp-off.index');
        Route::get('/comp-off/create', [Employee\CompOffController::class, 'create'])->name('comp-off.create');
        Route::post('/comp-off', [Employee\CompOffController::class, 'store'])->name('comp-off.store');

        /*
    |--------------------------------------------------------------------------
    | Mission Requests
    |--------------------------------------------------------------------------*/
        Route::get('/missions', [Employee\MissionController::class, 'index'])->name('missions.index');
        Route::get('/missions/create', [Employee\MissionController::class, 'create'])->name('missions.create');
        Route::post('/missions', [Employee\MissionController::class, 'store'])->name('missions.store');



        /*
    |--------------------------------------------------------------------------
    |shift-change Requests
    |--------------------------------------------------------------------------*/
        Route::get('/shift-changes', [Employee\ShiftChangeController::class, 'index'])->name('shift-changes.index');
        Route::get('/shift-changes/create', [Employee\ShiftChangeController::class, 'create'])->name('shift-changes.create');
        Route::post('/shift-changes', [Employee\ShiftChangeController::class, 'store'])->name('shift-changes.store');
    });



/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin,manager'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

        /*
    |--------------------------------------------------------------------------
    | Core System
    |--------------------------------------------------------------------------
    */

        Route::resource('employees', Admin\EmployeeController::class);
        Route::resource('departments', Admin\DepartmentController::class);
        Route::resource('shifts', Admin\ShiftController::class);
        Route::resource('holidays', Admin\HolidayController::class);
        Route::resource('leave-types', Admin\LeaveTypeController::class);


        /*
    |--------------------------------------------------------------------------
    | Leave Requests
    |--------------------------------------------------------------------------
    */

        Route::get('/leave-requests', [Admin\LeaveRequestController::class, 'index'])->name('leave-requests.index');
        Route::get('/leave-requests/{leaveRequest}', [Admin\LeaveRequestController::class, 'show'])->name('leave-requests.show');
        Route::post('/leave-requests/{leaveRequest}/approve', [Admin\LeaveRequestController::class, 'approve'])->name('leave-requests.approve');
        Route::post('/leave-requests/{leaveRequest}/reject', [Admin\LeaveRequestController::class, 'reject'])->name('leave-requests.reject');

        /*
    |--------------------------------------------------------------------------
    | Permission Requests
    |--------------------------------------------------------------------------
    */

        Route::get('/permission-requests', [Admin\PermissionRequestController::class, 'index'])->name('permission-requests.index');
        Route::get('/permission-requests/{permissionRequest}', [Admin\PermissionRequestController::class, 'show'])->name('permission-requests.show');
        Route::post('/permission-requests/{permissionRequest}/approve', [Admin\PermissionRequestController::class, 'approve'])->name('permission-requests.approve');
        Route::post('/permission-requests/{permissionRequest}/reject', [Admin\PermissionRequestController::class, 'reject'])->name('permission-requests.reject');



        /*
    |--------------------------------------------------------------------------
    | CompOffRequests
    |--------------------------------------------------------------------------
    */

        Route::get('/comp-off-requests', [Admin\CompOffRequestController::class, 'index'])->name('comp-off-requests.index');
        Route::get('/comp-off-requests/{compOffRequest}', [Admin\CompOffRequestController::class, 'show'])->name('comp-off-requests.show');
        Route::post('/comp-off-requests/{compOffRequest}/approve', [Admin\CompOffRequestController::class, 'approve'])->name('comp-off-requests.approve');
        Route::post('/comp-off-requests/{compOffRequest}/reject', [Admin\CompOffRequestController::class, 'reject'])->name('comp-off-requests.reject');


        /*
    |--------------------------------------------------------------------------
    | Mission Requests
    |--------------------------------------------------------------------------
    */

        Route::get('/mission-requests', [Admin\MissionRequestController::class, 'index'])->name('mission-requests.index');
        Route::get('/mission-requests/{missionRequest}', [Admin\MissionRequestController::class, 'show'])->name('mission-requests.show');
        Route::post('/mission-requests/{missionRequest}/approve', [Admin\MissionRequestController::class, 'approve'])->name('mission-requests.approve');
        Route::post('/mission-requests/{missionRequest}/reject', [Admin\MissionRequestController::class, 'reject'])->name('mission-requests.reject');



        /*
    |--------------------------------------------------------------------------
    | Shift Change Requests
    |--------------------------------------------------------------------------
    */

        Route::get('/shift-change-requests', [Admin\ShiftChangeRequestController::class, 'index'])->name('shift-change-requests.index');
        Route::get('/shift-change-requests/{shiftChangeRequest}', [Admin\ShiftChangeRequestController::class, 'show'])->name('shift-change-requests.show');
        Route::post('/shift-change-requests/{shiftChangeRequest}/approve', [Admin\ShiftChangeRequestController::class, 'approve'])->name('shift-change-requests.approve');
        Route::post('/shift-change-requests/{shiftChangeRequest}/reject', [Admin\ShiftChangeRequestController::class, 'reject'])->name('shift-change-requests.reject');
        /*
    |--------------------------------------------------------------------------
    | Reports (Old System)
    |--------------------------------------------------------------------------
    */

        Route::prefix('reports')->name('reports.')->group(function () {

            Route::get('/monthly', [Admin\ReportController::class, 'monthly'])->name('monthly');
            Route::get('/monthly/pdf', [Admin\ReportController::class, 'exportMonthlyPdf'])->name('monthly.pdf');
            Route::get('/monthly/excel', [Admin\ReportController::class, 'exportMonthlyExcel'])->name('monthly.excel');

            Route::get('/late', [Admin\ReportController::class, 'lateReport'])->name('late');
            Route::get('/department', [Admin\ReportController::class, 'departmentReport'])->name('department');
        });


        /*
    |--------------------------------------------------------------------------
    | New Sections (UI Only)
    |--------------------------------------------------------------------------
    */

        Route::view('/requests', 'admin.requests.index')->name('requests.index');
        Route::get('/inquiries', [Admin\InquiryController::class, 'index'])->name('inquiries.index');
        Route::view('/work-system', 'admin.work-system.index')->name('work-system.index');
        // Route::view('/reports-center', 'admin.reports.index')->name('reports.index');

        Route::get('/attendance', [Admin\AttendanceController::class, 'index'])->name('attendance.index');
        Route::get('/leave-balances', [Admin\LeaveBalanceController::class, 'index'])->name('leave-balances.index');
        Route::get('/audit-logs', [Admin\AuditLogController::class, 'index'])->name('audit-logs.index');

        Route::get('/reports-center/export/excel', [Admin\ReportsCenterController::class, 'exportExcel'])->name('reports.export.excel');
        Route::get('/reports-center/export/pdf', [Admin\ReportsCenterController::class, 'exportPdf'])->name('reports.export.pdf');
        Route::get('/reports-center', [Admin\ReportsCenterController::class, 'index'])->name('reports.index');
        Route::get('/notifications', [Admin\NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{notification}/read', [Admin\NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [Admin\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
        Route::resource('work-schedules', Admin\WorkScheduleController::class)->except(['show']);
        Route::resource('financial-policies', Admin\FinancialPolicyController::class)->except(['show']);
    });


/*
|--------------------------------------------------------------------------
| Dashboard Redirect
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->get('/dashboard', function () {
    return auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager')
        ? redirect()->route('admin.dashboard')
        : redirect()->route('employee.dashboard');
})->name('dashboard');
