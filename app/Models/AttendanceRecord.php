<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'date', 'check_in', 'check_out',
        'status', 'late_minutes', 'early_leave_minutes', 'work_minutes', 'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'check_in' => 'datetime',
        'check_out' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'present' => 'حاضر',
            'absent'  => 'غائب',
            'late'    => 'متأخر',
            'leave'   => 'إجازة',
            'holiday' => 'عطلة',
            default   => $this->status,
        };
    }
}