<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin if it doesn't exist
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@hotel.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->assignRole('super_admin');

        // Create Admin if it doesn't exist
        $admin = User::firstOrCreate(
            ['email' => 'admin@hotel.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('admin');

        // Create Manager if it doesn't exist
        $manager = User::firstOrCreate(
            ['email' => 'manager@hotel.com'],
            [
                'name' => 'Manager',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $manager->assignRole('manager');

        // Create Staff if it doesn't exist
        $staff = User::firstOrCreate(
            ['email' => 'staff@hotel.com'],
            [
                'name' => 'Staff',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $staff->assignRole('staff');

        // Create User if it doesn't exist
        $user = User::firstOrCreate(
            ['email' => 'user@hotel.com'],
            [
                'name' => 'User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $user->assignRole('user');
    }
}
