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
        Schema::create('conversion_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('pieces_amount', 15, 2); // Amount of pieces to convert
            $table->decimal('cash_amount', 15, 2); // Equivalent cash amount
            $table->decimal('conversion_rate', 10, 4); // Rate at time of conversion
            $table->enum('status', ['pending', 'approved', 'rejected', 'processing', 'completed'])->default('pending');
            $table->enum('payment_method', ['orange_money', 'mtn_mobile_money', 'wave', 'bank_transfer', 'paypal'])->nullable();
            $table->string('payment_phone')->nullable(); // For mobile money
            $table->string('payment_email')->nullable(); // For PayPal
            $table->string('payment_account')->nullable(); // For bank transfer
            $table->text('payment_details')->nullable(); // JSON for additional details
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->dateTime('processed_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('admin_notes')->nullable();
            $table->string('transaction_reference')->nullable()->unique();
            $table->string('payment_proof')->nullable(); // File path for proof of payment
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
            $table->index('user_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversion_requests');
    }
};
