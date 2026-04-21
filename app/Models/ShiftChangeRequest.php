<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftChangeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'current_shift_id',
        'requested_shift_id',
        'effective_date',
        'reason',
        'status',
        'approved_by',
        'approved_at',
        'manager_notes',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function currentShift()
    {
        return $this->belongsTo(Shift::class, 'current_shift_id');
    }

    public function requestedShift()
    {
        return $this->belongsTo(Shift::class, 'requested_shift_id');
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
}