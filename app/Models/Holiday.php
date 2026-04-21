<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'name', 'name_ar', 'is_recurring'];

    protected $casts = [
        'date' => 'date',
    ];
}