<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles if they don't exist
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $manager = Role::firstOrCreate(['name' => 'manager']);
        $staff = Role::firstOrCreate(['name' => 'staff']);
        $user = Role::firstOrCreate(['name' => 'user']);

        // Get all permissions
        $permissions = Permission::all();

        // Assign all permissions to super_admin
        $superAdmin->syncPermissions($permissions);

        // Assign permissions to admin (all except some super admin specific ones)
        $adminPermissions = $permissions->filter(function ($permission) {
            return !str_contains($permission->name, 'role') &&
                   !str_contains($permission->name, 'permission');
        });
        $admin->syncPermissions($adminPermissions);

        // Assign permissions to manager
        $managerPermissions = $permissions->filter(function ($permission) {
            return !str_contains($permission->name, 'delete') &&
                   !str_contains($permission->name, 'role') &&
                   !str_contains($permission->name, 'permission');
        });
        $manager->syncPermissions($managerPermissions);

        // Assign permissions to staff
        $staffPermissions = $permissions->filter(function ($permission) {
            return (str_contains($permission->name, 'view') ||
                   str_contains($permission->name, 'list')) &&
                   !str_contains($permission->name, 'role') &&
                   !str_contains($permission->name, 'permission');
        });
        $staff->syncPermissions($staffPermissions);

        // User role has minimal permissions
        $userPermissions = $permissions->filter(function ($permission) {
            return str_contains($permission->name, 'view-own') ||
                   str_contains($permission->name, 'edit-own');
        });
        $user->syncPermissions($userPermissions);
    }
}
