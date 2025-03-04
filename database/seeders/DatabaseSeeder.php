<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // First create permissions and roles
            PermissionSeeder::class,
            RoleSeeder::class,

            // Then create admin users with roles
            AdminUserSeeder::class,

            // Then seed the rest of the data
            RoomTypeSeeder::class,
            RoomSeeder::class,
            GuestSeeder::class,
            BookingSeeder::class,
            PaymentSeeder::class,
        ]);
    }
}
