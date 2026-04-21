<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            DepartmentSeeder::class,
            ShiftSeeder::class,
            LeaveTypeSeeder::class,
            HolidaySeeder::class,
            UserSeeder::class,
        ]);
    }
}