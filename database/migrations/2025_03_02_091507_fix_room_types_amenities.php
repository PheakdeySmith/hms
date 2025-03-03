<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\RoomType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix any room types with string amenities
        $roomTypes = DB::table('room_types')->get();
        
        foreach ($roomTypes as $roomType) {
            $amenities = $roomType->amenities;
            
            // Check if amenities is a string but not a valid JSON array
            if (is_string($amenities) && !empty($amenities) && $amenities[0] !== '[') {
                // Convert to a JSON array with the string as a single element
                $newAmenities = json_encode([$amenities]);
                
                // Update the record
                DB::table('room_types')
                    ->where('id', $roomType->id)
                    ->update(['amenities' => $newAmenities]);
            }
            
            // Check if amenities is null
            if (is_null($amenities)) {
                // Set to empty array
                DB::table('room_types')
                    ->where('id', $roomType->id)
                    ->update(['amenities' => json_encode([])]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this migration as it's a data fix
    }
};
