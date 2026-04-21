<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Holiday;

class HolidaySeeder extends Seeder
{
    public function run(): void
    {
        $holidays = [
            ['date' => '2025-01-01', 'name' => "New Year's Day", 'name_ar' => 'رأس السنة الميلادية'],
            ['date' => '2025-04-25', 'name' => 'Sinai Liberation Day', 'name_ar' => 'عيد تحرير سيناء'],
            ['date' => '2025-05-01', 'name' => 'Labor Day', 'name_ar' => 'عيد العمال'],
            ['date' => '2025-07-23', 'name' => 'Revolution Day', 'name_ar' => 'ثورة 23 يوليو'],
            ['date' => '2025-10-06', 'name' => 'Armed Forces Day', 'name_ar' => 'عيد القوات المسلحة'],
        ];

        foreach ($holidays as $holiday) {
            Holiday::firstOrCreate(['date' => $holiday['date']], $holiday);
        }
    }
}