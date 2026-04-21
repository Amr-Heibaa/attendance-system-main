<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view dashboard',
            'manage employees',
            'manage departments',
            'manage shifts',
            'manage holidays',
            'manage leave-types',
            'approve leave-requests',
            'view reports',
            'export reports',
            'check in',
            'check out',
            'submit leave',
            'view own attendance',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        $employee = Role::firstOrCreate(['name' => 'employee']);
        $employee->givePermissionTo(['check in', 'check out', 'submit leave', 'view own attendance']);

        $manager = Role::firstOrCreate(['name' => 'manager']);
        $manager->givePermissionTo(['check in', 'check out', 'submit leave', 'view own attendance', 'approve leave-requests', 'view reports']);
    }
}