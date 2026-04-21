<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shift;

class ShiftSeeder extends Seeder
{
    public function run(): void
    {
        $shifts = [
            ['name' => 'الوردية الصباحية', 'start_time' => '08:00:00', 'end_time' => '16:00:00', 'grace_minutes' => 15],
            ['name' => 'الوردية المسائية', 'start_time' => '14:00:00', 'end_time' => '22:00:00', 'grace_minutes' => 15],
            ['name' => 'الوردية الليلية', 'start_time' => '22:00:00', 'end_time' => '06:00:00', 'grace_minutes' => 15],
        ];

        foreach ($shifts as $shift) {
            Shift::firstOrCreate(['name' => $shift['name']], $shift);
        }
    }
}