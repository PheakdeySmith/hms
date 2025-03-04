<?php

namespace Database\Seeders;

use App\Models\RoomType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roomTypes = [
            [
                'name' => 'Standard Single',
                'description' => 'A cozy room with a single bed, perfect for solo travelers.',
                'base_price' => 89.99,
                'capacity' => 1,
                'has_air_conditioning' => true,
                'has_tv' => true,
                'has_refrigerator' => false,
                'has_safe' => false,
                'has_wifi' => true,
                'has_minibar' => false,
                'has_bathtub' => false,
                'amenities' => json_encode(['Free WiFi', 'TV', 'Air Conditioning', 'Desk', 'Wardrobe']),
                'image' => 'room_types/standard_single.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Standard Double',
                'description' => 'A comfortable room with a double bed, suitable for couples or solo travelers who prefer more space.',
                'base_price' => 119.99,
                'capacity' => 2,
                'has_air_conditioning' => true,
                'has_tv' => true,
                'has_refrigerator' => true,
                'has_safe' => true,
                'has_wifi' => true,
                'has_minibar' => false,
                'has_bathtub' => false,
                'amenities' => json_encode(['Free WiFi', 'TV', 'Air Conditioning', 'Refrigerator', 'Safe', 'Desk', 'Wardrobe']),
                'image' => 'room_types/standard_double.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Deluxe Double',
                'description' => 'A spacious room with a queen-size bed and additional amenities for a more luxurious stay.',
                'base_price' => 159.99,
                'capacity' => 2,
                'has_air_conditioning' => true,
                'has_tv' => true,
                'has_refrigerator' => true,
                'has_safe' => true,
                'has_wifi' => true,
                'has_minibar' => true,
                'has_bathtub' => true,
                'amenities' => json_encode(['Free WiFi', 'TV', 'Air Conditioning', 'Refrigerator', 'Safe', 'Minibar', 'Bathtub', 'Desk', 'Wardrobe', 'Coffee Machine']),
                'image' => 'room_types/deluxe_double.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Family Suite',
                'description' => 'A large suite with a king-size bed and a sofa bed, perfect for families or groups.',
                'base_price' => 229.99,
                'capacity' => 4,
                'has_air_conditioning' => true,
                'has_tv' => true,
                'has_refrigerator' => true,
                'has_safe' => true,
                'has_wifi' => true,
                'has_minibar' => true,
                'has_bathtub' => true,
                'amenities' => json_encode(['Free WiFi', 'TV', 'Air Conditioning', 'Refrigerator', 'Safe', 'Minibar', 'Bathtub', 'Desk', 'Wardrobe', 'Coffee Machine', 'Sofa', 'Dining Area']),
                'image' => 'room_types/family_suite.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Executive Suite',
                'description' => 'A premium suite with a king-size bed, separate living area, and exclusive amenities for a luxurious experience.',
                'base_price' => 299.99,
                'capacity' => 2,
                'has_air_conditioning' => true,
                'has_tv' => true,
                'has_refrigerator' => true,
                'has_safe' => true,
                'has_wifi' => true,
                'has_minibar' => true,
                'has_bathtub' => true,
                'amenities' => json_encode(['Free WiFi', 'TV', 'Air Conditioning', 'Refrigerator', 'Safe', 'Minibar', 'Bathtub', 'Desk', 'Wardrobe', 'Coffee Machine', 'Sofa', 'Dining Area', 'Jacuzzi', 'Balcony']),
                'image' => 'room_types/executive_suite.jpg',
                'is_active' => true,
            ],
        ];

        foreach ($roomTypes as $roomType) {
            RoomType::firstOrCreate(
                ['name' => $roomType['name']],
                $roomType
            );
        }
    }
}
