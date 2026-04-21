<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\CompOffRequest;
use App\Models\LeaveRequest;
use App\Models\MissionRequest;
use App\Models\Notification;
use App\Models\PermissionRequest;
use App\Models\ShiftChangeRequest;

class NotificationController extends Controller
{
    public function index()
    {
        $stats = [
            'pending_leaves' => LeaveRequest::where('status', 'pending')->count(),
            'pending_permissions' => PermissionRequest::where('status', 'pending')->count(),
            'pending_missions' => MissionRequest::where('status', 'pending')->count(),
            'pending_comp_off' => CompOffRequest::where('status', 'pending')->count(),
            'pending_shift_changes' => ShiftChangeRequest::where('status', 'pending')->count(),

            'late_today' => AttendanceRecord::whereDate('date', today())
                ->where('status', 'late')
                ->count(),

            'incomplete_today' => AttendanceRecord::whereDate('date', today())
                ->whereNotNull('check_in')
                ->whereNull('check_out')
                ->count(),
        ];

        $pendingLeaves = LeaveRequest::with('employee.user')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $pendingPermissions = PermissionRequest::with('employee.user')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $pendingMissions = MissionRequest::with('employee.user')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $pendingCompOff = CompOffRequest::with('employee.user')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $pendingShiftChanges = ShiftChangeRequest::with([
                'employee.user',
                'currentShift',
                'requestedShift'
            ])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $lateToday = AttendanceRecord::with('employee.user')
            ->whereDate('date', today())
            ->where('status', 'late')
            ->latest()
            ->take(5)
            ->get();

        $incompleteToday = AttendanceRecord::with('employee.user')
            ->whereDate('date', today())
            ->whereNotNull('check_in')
            ->whereNull('check_out')
            ->latest()
            ->take(5)
            ->get();

        // التنبيهات الحقيقية المحفوظة
        $notifications = Notification::latest()->paginate(10);

        return view('admin.notifications.index', compact(
            'stats',
            'pendingLeaves',
            'pendingPermissions',
            'pendingMissions',
            'pendingCompOff',
            'pendingShiftChanges',
            'lateToday',
            'incompleteToday',
            'notifications'
        ));
    }

    public function markAsRead(Notification $notification)
    {
        $notification->update([
            'is_read' => true,
        ]);

        return back()->with('success', 'تم تحديث حالة التنبيه');
    }

    public function markAllAsRead()
    {
        Notification::where('is_read', false)->update([
            'is_read' => true,
        ]);

        return back()->with('success', 'تم تحديد كل التنبيهات كمقروءة');
    }
}