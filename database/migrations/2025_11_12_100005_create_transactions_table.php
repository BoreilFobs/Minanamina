<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('payment_method_id')->constrained('payment_methods')->onDelete('restrict');
            $table->enum('type', ['withdrawal', 'deposit', 'refund'])->default('withdrawal');
            $table->decimal('amount', 15, 2);
            $table->decimal('pieces_converted', 15, 2);
            $table->decimal('fee', 10, 2)->default(0);
            $table->decimal('net_amount', 15, 2);
            $table->enum('status', ['pending', 'approved', 'completed', 'failed', 'rejected', 'cancelled'])->default('pending');
            $table->string('reference_id')->unique();
            $table->string('payment_reference')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
            $table->index('user_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
