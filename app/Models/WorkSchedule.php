<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'grace_minutes',
        'work_days',
        'effective_from',
        'effective_to',
        'is_default',
        'is_active',
        'approved_by',
        'notes',
    ];

    protected $casts = [
        'work_days' => 'array',
        'effective_from' => 'date',
        'effective_to' => 'date',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public static function getActiveForDate($date = null): ?self
{
    $date = $date ? \Carbon\Carbon::parse($date)->toDateString() : now()->toDateString();
    $dayName = \Carbon\Carbon::parse($date)->format('l');

    return self::query()
        ->where('is_active', true)
        ->whereDate('effective_from', '<=', $date)
        ->where(function ($q) use ($date) {
            $q->whereNull('effective_to')
              ->orWhereDate('effective_to', '>=', $date);
        })
        ->orderByDesc('is_default')
        ->orderByDesc('id')
        ->get()
        ->first(function ($schedule) use ($dayName) {
            $days = $schedule->work_days ?? [];
            return empty($days) || in_array($dayName, $days);
        });
}
}