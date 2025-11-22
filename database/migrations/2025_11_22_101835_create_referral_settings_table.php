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
        Schema::create('referral_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('value');
            $table->string('type')->default('string'); // string, integer, boolean, decimal
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default referral bonus setting
        DB::table('referral_settings')->insert([
            [
                'key' => 'referral_bonus_amount',
                'value' => '500',
                'type' => 'integer',
                'description' => 'Pieces awarded to referrer when someone uses their code',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'referral_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Enable or disable referral system',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_settings');
    }
};
