<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Human Resources', 'name_ar' => 'الموارد البشرية'],
            ['name' => 'Information Technology', 'name_ar' => 'تقنية المعلومات'],
            ['name' => 'Finance', 'name_ar' => 'المالية'],
            ['name' => 'Marketing', 'name_ar' => 'التسويق'],
            ['name' => 'Operations', 'name_ar' => 'العمليات'],
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate(['name' => $dept['name']], $dept);
        }
    }
}