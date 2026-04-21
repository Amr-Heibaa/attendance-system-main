<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompOffRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'worked_on_date',
        'requested_off_date',
        'reason',
        'status',
        'approved_by',
        'approved_at',
        'manager_notes',
    ];

    protected $casts = [
        'worked_on_date' => 'date',
        'requested_off_date' => 'date',
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
}