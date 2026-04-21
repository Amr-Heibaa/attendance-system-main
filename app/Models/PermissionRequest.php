<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'from_time',
        'to_time',
        'minutes_count',
        'type',
        'reason',
        'status',
        'approved_by',
        'approved_at',
        'manager_notes',
    ];

    protected $casts = [
        'date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'قيد المراجعة',
            'approved' => 'مقبول',
            'rejected' => 'مرفوض',
            default => $this->status,
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'late_arrival' => 'تأخير حضور',
            'early_leave' => 'انصراف مبكر',
            'temporary_exit' => 'خروج مؤقت',
            default => $this->type,
        };
    }
}