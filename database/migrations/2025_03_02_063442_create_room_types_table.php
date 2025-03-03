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
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('base_price', 10, 2);
            $table->integer('capacity');
            $table->boolean('has_air_conditioning')->default(false);
            $table->boolean('has_tv')->default(false);
            $table->boolean('has_refrigerator')->default(false);
            $table->boolean('has_safe')->default(false);
            $table->boolean('has_wifi')->default(true);
            $table->boolean('has_minibar')->default(false);
            $table->boolean('has_bathtub')->default(false);
            $table->json('amenities')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_types');
    }
};
