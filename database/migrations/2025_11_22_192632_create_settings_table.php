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
            $table->string('type')->default('string'); // string, number, boolean, json
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('settings')->insert([
            [
                'key' => 'conversion_rate',
                'value' => '0.001',
                'type' => 'number',
                'description' => 'Taux de conversion des pièces en FCFA (1 pièce = X FCFA)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'minimum_conversion_pieces',
                'value' => '10000',
                'type' => 'number',
                'description' => 'Nombre minimum de pièces requis pour une conversion',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'conversion_enabled',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Activer/désactiver le système de conversion',
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
        Schema::dropIfExists('settings');
    }
};
