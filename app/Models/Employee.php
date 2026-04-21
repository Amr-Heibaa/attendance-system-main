<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id',
    'employee_code',
    'department_id',
    'shift_id',
    'manager_id',
    'job_title',
    'phone',
    'emergency_phone',
    'national_id',
    'birth_date',
    'insurance_number',
    'education_qualification',
    'address',
    'cost_center',
    'work_location',
    'hire_date',
    'status',
];

   protected $casts = [
    'hire_date' => 'date',
    'birth_date' => 'date',
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    public function subordinates()
    {
        return $this->hasMany(Employee::class, 'manager_id');
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function todayAttendance()
    {
        return $this->hasOne(AttendanceRecord::class)->whereDate('date', today());
    }

    public function permissionRequests()
    {
        return $this->hasMany(PermissionRequest::class);
    }

    public function compOffRequests()
    {
        return $this->hasMany(CompOffRequest::class);
    }

    public function missionRequests()
    {
        return $this->hasMany(\App\Models\MissionRequest::class);
    }

    public function shiftChangeRequests()
    {
        return $this->hasMany(\App\Models\ShiftChangeRequest::class);
    }
}
