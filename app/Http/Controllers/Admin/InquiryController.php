<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\MissionRequest;
use App\Models\PermissionRequest;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    private function applyEmployeeSearchToEmployeeQuery($query, ?string $search)
    {
        return $query->when($search, function ($q) use ($search) {
            $q->where(function ($subQ) use ($search) {
                $subQ->where('employee_code', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQ) use ($search) {
                        $userQ->where('name', 'like', "%{$search}%");
                    });
            });
        });
    }

    private function applyEmployeeSearchToRelatedEmployeeQuery($query, ?string $search)
    {
        return $query->when($search, function ($q) use ($search) {
            $q->whereHas('employee', function ($employeeQ) use ($search) {
                $employeeQ->where(function ($subQ) use ($search) {
                    $subQ->where('employee_code', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQ) use ($search) {
                            $userQ->where('name', 'like', "%{$search}%");
                        });
                });
            });
        });
    }

    public function index(Request $request)
    {
        $type = $request->type ?? 'leaves';
        $from = $request->from;
        $to = $request->to;
        $employeeId = $request->employee_id;
        $search = $request->search;

        $deptId = auth()->user()->hasRole('manager')
            ? auth()->user()->employee?->department_id
            : null;

        $employees = Employee::with('user')
            ->when($deptId, fn($q) => $q->where('department_id', $deptId))
            ->where('status', 'active');

        $this->applyEmployeeSearchToEmployeeQuery($employees, $search);

        $employees = $employees->get();

        $results = collect();

        if ($type === 'leaves') {
            $query = LeaveRequest::with(['employee.user', 'leaveType'])
                ->when($deptId, fn($q) => $q->whereHas('employee', fn($e) => $e->where('department_id', $deptId)))
                ->when($employeeId, fn($q) => $q->where('employee_id', $employeeId))
                ->when($from, fn($q) => $q->whereDate('start_date', '>=', $from))
                ->when($to, fn($q) => $q->whereDate('end_date', '<=', $to))
                ->latest();

            $this->applyEmployeeSearchToRelatedEmployeeQuery($query, $search);

            $results = $query->get();
        }

        if ($type === 'missions') {
            $query = MissionRequest::with(['employee.user'])
                ->when($deptId, fn($q) => $q->whereHas('employee', fn($e) => $e->where('department_id', $deptId)))
                ->when($employeeId, fn($q) => $q->where('employee_id', $employeeId))
                ->when($from, fn($q) => $q->whereDate('date', '>=', $from))
                ->when($to, fn($q) => $q->whereDate('date', '<=', $to))
                ->latest();

            $this->applyEmployeeSearchToRelatedEmployeeQuery($query, $search);

            $results = $query->get();
        }

        if ($type === 'permissions') {
            $query = PermissionRequest::with(['employee.user'])
                ->when($deptId, fn($q) => $q->whereHas('employee', fn($e) => $e->where('department_id', $deptId)))
                ->when($employeeId, fn($q) => $q->where('employee_id', $employeeId))
                ->when($from, fn($q) => $q->whereDate('date', '>=', $from))
                ->when($to, fn($q) => $q->whereDate('date', '<=', $to))
                ->latest();

            $this->applyEmployeeSearchToRelatedEmployeeQuery($query, $search);

            $results = $query->get();
        }

        if ($type === 'late') {
            $query = AttendanceRecord::with(['employee.user'])
                ->when($deptId, fn($q) => $q->whereHas('employee', fn($e) => $e->where('department_id', $deptId)))
                ->when($employeeId, fn($q) => $q->where('employee_id', $employeeId))
                ->where('status', 'late')
                ->when($from, fn($q) => $q->whereDate('date', '>=', $from))
                ->when($to, fn($q) => $q->whereDate('date', '<=', $to))
                ->latest();

            $this->applyEmployeeSearchToRelatedEmployeeQuery($query, $search);

            $results = $query->get();
        }

        return view('admin.inquiries.index', compact(
            'type',
            'from',
            'to',
            'employeeId',
            'search',
            'employees',
            'results'
        ));
    }

    public function exportExcel(Request $request)
    {
        return back()->with('success', 'تصدير Excel تحت التنفيذ');
    }

    public function exportPdf(Request $request)
    {
        return back()->with('success', 'تصدير PDF تحت التنفيذ');
    }
}