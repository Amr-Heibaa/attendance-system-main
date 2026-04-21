<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MissionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'title',
        'type',
        'date',
        'from_time',
        'to_time',
        'minutes_count',
        'location',
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
            'internal' => 'مهمة داخلية',
            'external' => 'مهمة خارجية',
            default => $this->type,
        };
    }
}