<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LeaveType;

class LeaveTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Annual Leave', 'name_ar' => 'إجازة سنوية', 'max_days_per_year' => 21, 'is_paid' => true],
            ['name' => 'Sick Leave', 'name_ar' => 'إجازة مرضية', 'max_days_per_year' => 15, 'is_paid' => true],
            ['name' => 'Unpaid Leave', 'name_ar' => 'إجازة بدون راتب', 'max_days_per_year' => 30, 'is_paid' => false],
            ['name' => 'Emergency Leave', 'name_ar' => 'إجازة طارئة', 'max_days_per_year' => 3, 'is_paid' => true],
        ];

        foreach ($types as $type) {
            LeaveType::firstOrCreate(['name' => $type['name']], $type);
        }
    }
}