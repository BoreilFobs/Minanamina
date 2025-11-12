<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('provider'); // orange_money, mtn_money, wave, paypal, bank_card, prepaid_code
            $table->text('description')->nullable();
            $table->json('configuration')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);
            $table->decimal('minimum_amount', 10, 2)->default(0);
            $table->decimal('maximum_amount', 10, 2)->nullable();
            $table->decimal('transaction_fee_percentage', 5, 2)->default(0);
            $table->decimal('transaction_fee_fixed', 10, 2)->default(0);
            $table->timestamps();
            
            $table->unique('provider');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
