<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'name_ar', 'max_days_per_year', 'is_paid', 'is_active'];

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }
}