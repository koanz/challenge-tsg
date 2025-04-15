<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Permissions endpoints of users
        $permissionAdminUsers = Permission::create([
            'name' => 'admin'
        ]);

        $permissionUsers = Permission::create([
            'name' => 'users'
        ]);

        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        $adminRole->givePermissionTo($permissionAdminUsers);
        $userRole->givePermissionTo($permissionUsers);
    }
}
