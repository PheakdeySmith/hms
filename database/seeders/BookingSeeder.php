<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use App\Models\RoomType;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $guests = Guest::all();
        $rooms = Room::all();
        
        // Create past bookings (checked out)
        for ($i = 0; $i < 10; $i++) {
            $guest = $guests->random();
            $room = $rooms->where('status', 'available')->random();
            $roomType = $room->roomType;
            
            $checkInDate = Carbon::now()->subDays(rand(10, 30));
            $checkOutDate = (clone $checkInDate)->addDays(rand(1, 7));
            $numberOfGuests = rand(1, $roomType->capacity);
            $totalPrice = $roomType->base_price * $checkInDate->diffInDays($checkOutDate);
            
            Booking::create([
                'guest_id' => $guest->id,
                'room_id' => $room->id,
                'check_in_date' => $checkInDate,
                'check_out_date' => $checkOutDate,
                'actual_check_in' => $checkInDate->addHours(rand(14, 18)),
                'actual_check_out' => $checkOutDate->addHours(rand(8, 11)),
                'number_of_guests' => $numberOfGuests,
                'total_price' => $totalPrice,
                'status' => 'checked_out',
                'special_requests' => $faker->optional(0.3)->sentence,
                'booking_source' => $faker->randomElement(['direct', 'booking.com', 'expedia', 'phone']),
                'booking_reference' => $faker->unique()->bothify('??####'),
            ]);
        }
        
        // Create current bookings (checked in)
        for ($i = 0; $i < 3; $i++) {
            $guest = $guests->random();
            $room = $rooms->where('status', 'occupied')->random();
            $roomType = $room->roomType;
            
            $checkInDate = Carbon::now()->subDays(rand(1, 3));
            $checkOutDate = Carbon::now()->addDays(rand(1, 4));
            $numberOfGuests = rand(1, $roomType->capacity);
            $totalPrice = $roomType->base_price * $checkInDate->diffInDays($checkOutDate);
            
            Booking::create([
                'guest_id' => $guest->id,
                'room_id' => $room->id,
                'check_in_date' => $checkInDate,
                'check_out_date' => $checkOutDate,
                'actual_check_in' => $checkInDate->addHours(rand(14, 18)),
                'actual_check_out' => null,
                'number_of_guests' => $numberOfGuests,
                'total_price' => $totalPrice,
                'status' => 'checked_in',
                'special_requests' => $faker->optional(0.3)->sentence,
                'booking_source' => $faker->randomElement(['direct', 'booking.com', 'expedia', 'phone']),
                'booking_reference' => $faker->unique()->bothify('??####'),
            ]);
        }
        
        // Create future bookings (confirmed)
        for ($i = 0; $i < 5; $i++) {
            $guest = $guests->random();
            $room = $rooms->where('status', 'available')->random();
            $roomType = $room->roomType;
            
            $checkInDate = Carbon::now()->addDays(rand(1, 30));
            $checkOutDate = (clone $checkInDate)->addDays(rand(1, 7));
            $numberOfGuests = rand(1, $roomType->capacity);
            $totalPrice = $roomType->base_price * $checkInDate->diffInDays($checkOutDate);
            
            Booking::create([
                'guest_id' => $guest->id,
                'room_id' => $room->id,
                'check_in_date' => $checkInDate,
                'check_out_date' => $checkOutDate,
                'actual_check_in' => null,
                'actual_check_out' => null,
                'number_of_guests' => $numberOfGuests,
                'total_price' => $totalPrice,
                'status' => 'confirmed',
                'special_requests' => $faker->optional(0.3)->sentence,
                'booking_source' => $faker->randomElement(['direct', 'booking.com', 'expedia', 'phone']),
                'booking_reference' => $faker->unique()->bothify('??####'),
            ]);
        }
        
        // Create a booking for today's check-in
        $guest = Guest::where('email', 'john.doe@example.com')->first();
        $room = Room::where('room_number', '401')->first();
        $roomType = $room->roomType;
        
        $checkInDate = Carbon::today();
        $checkOutDate = (clone $checkInDate)->addDays(3);
        $totalPrice = $roomType->base_price * $checkInDate->diffInDays($checkOutDate);
        
        Booking::create([
            'guest_id' => $guest->id,
            'room_id' => $room->id,
            'check_in_date' => $checkInDate,
            'check_out_date' => $checkOutDate,
            'actual_check_in' => null,
            'actual_check_out' => null,
            'number_of_guests' => 2,
            'total_price' => $totalPrice,
            'status' => 'confirmed',
            'special_requests' => 'Early check-in requested',
            'booking_source' => 'direct',
            'booking_reference' => 'DIR1234',
        ]);
        
        // Create a booking for today's check-out
        $guest = Guest::where('email', 'jane.smith@example.com')->first();
        $room = Room::where('room_number', '201')->first();
        $roomType = $room->roomType;
        
        $checkInDate = Carbon::today()->subDays(2);
        $checkOutDate = Carbon::today();
        $totalPrice = $roomType->base_price * $checkInDate->diffInDays($checkOutDate);
        
        Booking::create([
            'guest_id' => $guest->id,
            'room_id' => $room->id,
            'check_in_date' => $checkInDate,
            'check_out_date' => $checkOutDate,
            'actual_check_in' => $checkInDate->addHours(15),
            'actual_check_out' => null,
            'number_of_guests' => 2,
            'total_price' => $totalPrice,
            'status' => 'checked_in',
            'special_requests' => 'Late check-out requested',
            'booking_source' => 'direct',
            'booking_reference' => 'DIR5678',
        ]);
    }
}
