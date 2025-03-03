<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Payment;
use Carbon\Carbon;
use Faker\Factory as Faker;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        
        // Get all bookings
        $bookings = Booking::all();
        
        // Payment methods
        $paymentMethods = ['credit_card', 'debit_card', 'cash', 'bank_transfer', 'paypal'];
        
        // Payment statuses
        $paymentStatuses = ['pending', 'completed', 'failed', 'refunded'];
        
        // Create payments for past bookings (checked out)
        $pastBookings = Booking::where('status', 'checked_out')->get();
        foreach ($pastBookings as $booking) {
            // Create full payment for past bookings
            Payment::create([
                'booking_id' => $booking->id,
                'amount' => $booking->total_price,
                'payment_method' => $faker->randomElement($paymentMethods),
                'status' => 'completed',
                'transaction_id' => $faker->uuid,
                'payment_date' => Carbon::parse($booking->check_out_date)->subDays(1),
                'notes' => $faker->optional(0.3)->sentence,
            ]);
        }
        
        // Create payments for current bookings (checked in)
        $currentBookings = Booking::where('status', 'checked_in')->get();
        foreach ($currentBookings as $booking) {
            // Create deposit payment (50% of total)
            Payment::create([
                'booking_id' => $booking->id,
                'amount' => $booking->total_price * 0.5,
                'payment_method' => $faker->randomElement($paymentMethods),
                'status' => 'completed',
                'transaction_id' => $faker->uuid,
                'payment_date' => Carbon::parse($booking->check_in_date),
                'notes' => 'Deposit payment',
            ]);
            
            // Some may have paid in full
            if ($faker->boolean(30)) {
                Payment::create([
                    'booking_id' => $booking->id,
                    'amount' => $booking->total_price * 0.5,
                    'payment_method' => $faker->randomElement($paymentMethods),
                    'status' => 'completed',
                    'transaction_id' => $faker->uuid,
                    'payment_date' => Carbon::parse($booking->check_in_date)->addHours(2),
                    'notes' => 'Remaining balance',
                ]);
            }
        }
        
        // Create payments for future bookings (confirmed)
        $futureBookings = Booking::where('status', 'confirmed')->get();
        foreach ($futureBookings as $booking) {
            // Create deposit payment (20% of total)
            Payment::create([
                'booking_id' => $booking->id,
                'amount' => $booking->total_price * 0.2,
                'payment_method' => $faker->randomElement($paymentMethods),
                'status' => 'completed',
                'transaction_id' => $faker->uuid,
                'payment_date' => Carbon::now()->subDays($faker->numberBetween(1, 30)),
                'notes' => 'Booking deposit',
            ]);
        }
        
        // Create specific test payments
        
        // For John Doe's booking (today's check-in)
        $johnBooking = Booking::whereHas('guest', function($query) {
            $query->where('first_name', 'John')->where('last_name', 'Doe');
        })->first();
        
        if ($johnBooking) {
            Payment::create([
                'booking_id' => $johnBooking->id,
                'amount' => $johnBooking->total_price * 0.5,
                'payment_method' => 'credit_card',
                'status' => 'completed',
                'transaction_id' => 'CC-'.strtoupper($faker->bothify('??####')),
                'payment_date' => Carbon::now()->subDays(7),
                'notes' => 'Deposit for early check-in',
            ]);
        }
        
        // For Jane Smith's booking (today's check-out)
        $janeBooking = Booking::whereHas('guest', function($query) {
            $query->where('first_name', 'Jane')->where('last_name', 'Smith');
        })->first();
        
        if ($janeBooking) {
            // Initial payment
            Payment::create([
                'booking_id' => $janeBooking->id,
                'amount' => $janeBooking->total_price * 0.7,
                'payment_method' => 'paypal',
                'status' => 'completed',
                'transaction_id' => 'PP-'.strtoupper($faker->bothify('??####')),
                'payment_date' => Carbon::parse($janeBooking->check_in_date),
                'notes' => 'Initial payment at check-in',
            ]);
            
            // Final payment
            Payment::create([
                'booking_id' => $janeBooking->id,
                'amount' => $janeBooking->total_price * 0.3,
                'payment_method' => 'credit_card',
                'status' => 'completed',
                'transaction_id' => 'CC-'.strtoupper($faker->bothify('??####')),
                'payment_date' => Carbon::now(),
                'notes' => 'Final payment at check-out with late check-out fee',
            ]);
        }
    }
}
