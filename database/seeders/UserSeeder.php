<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Shift;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@attendance.com'],
            [
                'name' => 'مدير النظام',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $admin->assignRole('admin');

        // Sample Employees
        $dept = Department::first();
        $shift = Shift::first();

        $employeesData = [
            [
                'name' => 'أحمد محمد علي',
                'email' => 'ahmed@attendance.com',
                'code' => 'EMP001',
                'job_title' => 'مهندس برمجيات',
                'phone' => '01011111111',
                'emergency_phone' => '01099999991',
                'national_id' => '29801011234567',
                'birth_date' => '1998-01-01',
                'insurance_number' => 'INS1001',
                'education_qualification' => 'بكالوريوس هندسة برمجيات',
                'address' => 'القاهرة - مدينة نصر',
                'cost_center' => 'CC-IT-001',
                'work_location' => 'المقر الرئيسي',
            ],
            [
                'name' => 'فاطمة حسن إبراهيم',
                'email' => 'fatima@attendance.com',
                'code' => 'EMP002',
                'job_title' => 'محاسبة',
                'phone' => '01022222222',
                'emergency_phone' => '01099999992',
                'national_id' => '29702021234567',
                'birth_date' => '1997-02-02',
                'insurance_number' => 'INS1002',
                'education_qualification' => 'بكالوريوس تجارة',
                'address' => 'الجيزة - الدقي',
                'cost_center' => 'CC-FIN-001',
                'work_location' => 'فرع الجيزة',
            ],
            [
                'name' => 'محمد عبدالله سالم',
                'email' => 'mohammed@attendance.com',
                'code' => 'EMP003',
                'job_title' => 'مدير مشروع',
                'phone' => '01033333333',
                'emergency_phone' => '01099999993',
                'national_id' => '29503031234567',
                'birth_date' => '1995-03-03',
                'insurance_number' => 'INS1003',
                'education_qualification' => 'بكالوريوس إدارة أعمال',
                'address' => 'الإسكندرية - سموحة',
                'cost_center' => 'CC-PM-001',
                'work_location' => 'فرع الإسكندرية',
            ],
        ];

        foreach ($employeesData as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );

            $user->assignRole('employee');

            if (!$user->employee) {
                Employee::create([
                    'user_id' => $user->id,
                    'employee_code' => $data['code'],
                    'department_id' => $dept?->id,
                    'shift_id' => $shift?->id,
                    'job_title' => $data['job_title'],
                    'phone' => $data['phone'],
                    'emergency_phone' => $data['emergency_phone'],
                    'national_id' => $data['national_id'],
                    'birth_date' => $data['birth_date'],
                    'insurance_number' => $data['insurance_number'],
                    'education_qualification' => $data['education_qualification'],
                    'address' => $data['address'],
                    'cost_center' => $data['cost_center'],
                    'work_location' => $data['work_location'],
                    'hire_date' => now()->subMonths(rand(3, 24)),
                    'status' => 'active',
                ]);
            }
        }
    }
}