<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialPolicy extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'minutes_from',
        'minutes_to',
        'penalty_type',
        'penalty_value',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'penalty_value' => 'decimal:2',
    ];

    public static function getApplicablePolicy(string $type, ?int $minutes = null): ?self
{
    $query = self::query()
        ->where('is_active', true)
        ->where('type', $type);

    if (in_array($type, ['late', 'early_leave'])) {
        $minutes = $minutes ?? 0;

        $query->where(function ($q) use ($minutes) {
            $q->where(function ($sub) use ($minutes) {
                $sub->whereNotNull('minutes_from')
                    ->whereNotNull('minutes_to')
                    ->where('minutes_from', '<=', $minutes)
                    ->where('minutes_to', '>=', $minutes);
            });
        });
    }

    if ($type === 'absence') {
        $query->whereNull('minutes_from')->whereNull('minutes_to');
    }

    return $query->orderBy('minutes_from')->first();
}
}