<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix boolean fields in room_types table
        $roomTypes = DB::table('room_types')->get();

        foreach ($roomTypes as $roomType) {
            $updates = [];

            // List of boolean fields in room_types table
            $booleanFields = [
                'has_air_conditioning',
                'has_tv',
                'has_refrigerator',
                'has_safe',
                'has_wifi',
                'has_minibar',
                'has_bathtub',
                'is_active'
            ];

            foreach ($booleanFields as $field) {
                $value = $roomType->$field;

                // Fix non-boolean values
                if (!is_null($value) && !is_bool($value)) {
                    if (is_string($value)) {
                        // Convert string 'true'/'false' to boolean
                        $updates[$field] = in_array(strtolower($value), ['true', '1', 'yes', 'on']);
                    } else {
                        // Convert any other value to boolean
                        $updates[$field] = (bool)$value;
                    }
                }
            }

            // Update the record if there are changes
            if (!empty($updates)) {
                DB::table('room_types')
                    ->where('id', $roomType->id)
                    ->update($updates);
            }
        }

        // Fix boolean fields in rooms table
        $rooms = DB::table('rooms')->get();

        foreach ($rooms as $room) {
            $updates = [];

            // List of boolean fields in rooms table
            $booleanFields = [
                'is_smoking',
                'is_accessible'
            ];

            foreach ($booleanFields as $field) {
                $value = $room->$field;

                // Fix non-boolean values
                if (!is_null($value) && !is_bool($value)) {
                    if (is_string($value)) {
                        // Convert string 'true'/'false' to boolean
                        $updates[$field] = in_array(strtolower($value), ['true', '1', 'yes', 'on']);
                    } else {
                        // Convert any other value to boolean
                        $updates[$field] = (bool)$value;
                    }
                }
            }

            // Update the record if there are changes
            if (!empty($updates)) {
                DB::table('rooms')
                    ->where('id', $room->id)
                    ->update($updates);
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
