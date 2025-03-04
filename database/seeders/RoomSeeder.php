<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get room type IDs
        $roomTypes = RoomType::all();

        // Standard Single rooms (101-110)
        $standardSingleId = $roomTypes->where('name', 'Standard Single')->first()->id;
        for ($i = 1; $i <= 10; $i++) {
            Room::firstOrCreate(
                ['room_number' => '10' . $i],
                [
                    'room_type_id' => $standardSingleId,
                    'floor' => 1,
                    'status' => 'available',
                    'is_smoking' => $i > 5, // Rooms 106-110 are smoking rooms
                    'is_accessible' => $i == 10, // Room 110 is accessible
                ]
            );
        }

        // Standard Double rooms (201-210)
        $standardDoubleId = $roomTypes->where('name', 'Standard Double')->first()->id;
        for ($i = 1; $i <= 10; $i++) {
            Room::firstOrCreate(
                ['room_number' => '20' . $i],
                [
                    'room_type_id' => $standardDoubleId,
                    'floor' => 2,
                    'status' => 'available',
                    'is_smoking' => $i > 5, // Rooms 206-210 are smoking rooms
                    'is_accessible' => $i == 10, // Room 210 is accessible
                ]
            );
        }

        // Deluxe Double rooms (301-310)
        $deluxeDoubleId = $roomTypes->where('name', 'Deluxe Double')->first()->id;
        for ($i = 1; $i <= 10; $i++) {
            Room::firstOrCreate(
                ['room_number' => '30' . $i],
                [
                    'room_type_id' => $deluxeDoubleId,
                    'floor' => 3,
                    'status' => 'available',
                    'is_smoking' => $i > 8, // Rooms 309-310 are smoking rooms
                    'is_accessible' => $i == 10, // Room 310 is accessible
                ]
            );
        }

        // Family Suite rooms (401-405)
        $familySuiteId = $roomTypes->where('name', 'Family Suite')->first()->id;
        for ($i = 1; $i <= 5; $i++) {
            Room::firstOrCreate(
                ['room_number' => '40' . $i],
                [
                    'room_type_id' => $familySuiteId,
                    'floor' => 4,
                    'status' => 'available',
                    'is_smoking' => false, // No smoking in family suites
                    'is_accessible' => $i == 5, // Room 405 is accessible
                ]
            );
        }

        // Executive Suite rooms (501-505)
        $executiveSuiteId = $roomTypes->where('name', 'Executive Suite')->first()->id;
        for ($i = 1; $i <= 5; $i++) {
            Room::firstOrCreate(
                ['room_number' => '50' . $i],
                [
                    'room_type_id' => $executiveSuiteId,
                    'floor' => 5,
                    'status' => 'available',
                    'is_smoking' => false, // No smoking in executive suites
                    'is_accessible' => $i == 5, // Room 505 is accessible
                ]
            );
        }

        // Set a few rooms to different statuses for testing
        Room::where('room_number', '101')->update(['status' => 'occupied']);
        Room::where('room_number', '201')->update(['status' => 'occupied']);
        Room::where('room_number', '301')->update(['status' => 'maintenance']);
        Room::where('room_number', '401')->update(['status' => 'reserved']);
    }
}
