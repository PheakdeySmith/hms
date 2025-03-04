<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define all permissions by module
        $permissions = [
            // Dashboard
            'view-dashboard',

            // Roles & Permissions
            'view-role',
            'create-role',
            'edit-role',
            'delete-role',
            'view-permission',
            'create-permission',
            'edit-permission',
            'delete-permission',
            'assign-permission',

            // Users
            'view-user',
            'create-user',
            'edit-user',
            'delete-user',

            // Rooms
            'view-room',
            'create-room',
            'edit-room',
            'delete-room',

            // Room Types
            'view-room-type',
            'create-room-type',
            'edit-room-type',
            'delete-room-type',

            // Guests
            'view-guest',
            'create-guest',
            'edit-guest',
            'delete-guest',

            // Bookings
            'view-booking',
            'create-booking',
            'edit-booking',
            'delete-booking',
            'check-in',
            'check-out',
            'cancel-booking',
            'view-own-booking',
            'edit-own-booking',

            // Payments
            'view-payment',
            'create-payment',
            'edit-payment',
            'delete-payment',
            'view-own-payment',

            // Reports
            'view-report',
            'export-report',

            // Settings
            'view-setting',
            'edit-setting',

            // Staff
            'view-staff',
            'create-staff',
            'edit-staff',
            'delete-staff',

            // Amenities
            'view-amenity',
            'create-amenity',
            'edit-amenity',
            'delete-amenity',
        ];

        // Create permissions if they don't exist
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
