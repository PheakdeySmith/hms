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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        $this->insertDefaultSettings();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }

    /**
     * Insert default settings.
     */
    private function insertDefaultSettings(): void
    {
        $settings = [
            [
                'key' => 'hotel_name',
                'value' => 'Hotel Management System',
            ],
            [
                'key' => 'hotel_address',
                'value' => '123 Hotel Street, City, Country',
            ],
            [
                'key' => 'hotel_phone',
                'value' => '+1 234 567 8900',
            ],
            [
                'key' => 'hotel_email',
                'value' => 'info@hotel.com',
            ],
            [
                'key' => 'check_in_time',
                'value' => '14:00',
            ],
            [
                'key' => 'check_out_time',
                'value' => '11:00',
            ],
        ];

        DB::table('settings')->insert($settings);
    }
};
