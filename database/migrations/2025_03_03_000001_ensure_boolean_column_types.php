<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ensure room_types boolean columns have the correct type
        Schema::table('room_types', function (Blueprint $table) {
            $table->boolean('has_air_conditioning')->default(false)->change();
            $table->boolean('has_tv')->default(false)->change();
            $table->boolean('has_refrigerator')->default(false)->change();
            $table->boolean('has_safe')->default(false)->change();
            $table->boolean('has_wifi')->default(true)->change();
            $table->boolean('has_minibar')->default(false)->change();
            $table->boolean('has_bathtub')->default(false)->change();
            $table->boolean('is_active')->default(true)->change();
        });

        // Ensure rooms boolean columns have the correct type
        Schema::table('rooms', function (Blueprint $table) {
            $table->boolean('is_smoking')->default(false)->change();
            $table->boolean('is_accessible')->default(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this migration as it's ensuring correct column types
    }
};
