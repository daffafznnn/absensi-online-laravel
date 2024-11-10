<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat permission jika belum ada
        $permission1 = Permission::firstOrCreate(['name' => 'view dashboard']);
        $permission2 = Permission::firstOrCreate(['name' => 'manage users']);
        $permission3 = Permission::firstOrCreate(['name' => 'manage leaves']);
        $permission4 = Permission::firstOrCreate(['name' => 'approve leaves']);
        $permission5 = Permission::firstOrCreate(['name' => 'view reports']);

        // Membuat role jika belum ada
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleEmployee = Role::firstOrCreate(['name' => 'employee']);
        $roleSupervisor = Role::firstOrCreate(['name' => 'supervisor']);
        $roleHRD = Role::firstOrCreate(['name' => 'hrd']);

        // Memberikan permission kepada role
        $roleAdmin->givePermissionTo([
            $permission1,
            $permission2,
            $permission3,
            $permission4,
            $permission5
        ]);

        $roleEmployee->givePermissionTo([
            $permission1,
            $permission3
        ]);

        $roleSupervisor->givePermissionTo([
            $permission1,
            $permission3,
            $permission4
        ]);

        $roleHRD->givePermissionTo([
            $permission1,
            $permission3,
            $permission4,
            $permission5
        ]);

        // Menetapkan role ke user berdasarkan email
        $adminUser = User::where('email', 'admin@example.com')->first();
        if ($adminUser) {
            $adminUser->assignRole('admin');
        }

        $employeeUser = User::where('email', 'employee@example.com')->first();
        if ($employeeUser) {
            $employeeUser->assignRole('employee');
        }

        $supervisorUser = User::where('email', 'supervisor@example.com')->first();
        if ($supervisorUser) {
            $supervisorUser->assignRole('supervisor');
        }

        $hrdUser = User::where('email', 'hrd@example.com')->first();
        if ($hrdUser) {
            $hrdUser->assignRole('hrd');
        }
    }
}
