<?php

namespace Database\Seeders;

use App\Models\Guest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class GuestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Create 20 sample guests
        for ($i = 0; $i < 20; $i++) {
            $email = $faker->unique()->safeEmail;
            Guest::firstOrCreate(
                ['email' => $email],
                [
                    'first_name' => $faker->firstName,
                    'last_name' => $faker->lastName,
                    'phone' => $faker->phoneNumber,
                    'address' => $faker->streetAddress,
                    'city' => $faker->city,
                    'state' => $faker->state,
                    'country' => $faker->country,
                    'postal_code' => $faker->postcode,
                    'date_of_birth' => $faker->date('Y-m-d', '-18 years'),
                    'identification_type' => $faker->randomElement(['passport', 'id_card', 'driver_license']),
                    'identification_number' => $faker->unique()->numerify('ID########'),
                    'special_requests' => $faker->optional(0.3)->sentence,
                ]
            );
        }

        // Create a few specific guests for testing
        Guest::firstOrCreate(
            ['email' => 'john.doe@example.com'],
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'phone' => '555-123-4567',
                'address' => '123 Main St',
                'city' => 'New York',
                'state' => 'NY',
                'country' => 'USA',
                'postal_code' => '10001',
                'date_of_birth' => '1980-01-01',
                'identification_type' => 'passport',
                'identification_number' => 'P12345678',
                'special_requests' => 'Early check-in requested',
            ]
        );

        Guest::firstOrCreate(
            ['email' => 'jane.smith@example.com'],
            [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'phone' => '555-987-6543',
                'address' => '456 Park Ave',
                'city' => 'Los Angeles',
                'state' => 'CA',
                'country' => 'USA',
                'postal_code' => '90001',
                'date_of_birth' => '1985-05-15',
                'identification_type' => 'driver_license',
                'identification_number' => 'DL87654321',
                'special_requests' => 'Late check-out requested',
            ]
        );
    }
}
