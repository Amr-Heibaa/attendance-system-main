<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Services\AttendanceService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private AttendanceService $attendanceService) {}

    public function index(Request $request)
    {
        $employee = $request->user()->employee;

        if (!$employee) {
            abort(403, 'لا يوجد ملف موظف مرتبط بحسابك');
        }

        $todayRecord = $employee->todayAttendance;

        return view('employee.dashboard', compact('employee', 'todayRecord'));
    }

    public function checkIn(Request $request)
    {
        try {
            $employee = $request->user()->employee;
            $this->attendanceService->checkIn($employee);
            return redirect()->route('employee.dashboard')
                ->with('success', 'تم تسجيل الحضور بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('employee.dashboard')
                ->with('error', $e->getMessage());
        }
    }

    public function checkOut(Request $request)
    {
        try {
            $employee = $request->user()->employee;
            $this->attendanceService->checkOut($employee);
            return redirect()->route('employee.dashboard')
                ->with('success', 'تم تسجيل الانصراف بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('employee.dashboard')
                ->with('error', $e->getMessage());
        }
    }
}