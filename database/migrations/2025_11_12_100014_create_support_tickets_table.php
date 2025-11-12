<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->string('title');
            $table->text('description');
            $table->enum('category', ['account', 'payment', 'campaign', 'referral', 'technical', 'other'])->default('other');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['open', 'in_progress', 'waiting_user', 'resolved', 'closed'])->default('open');
            $table->dateTime('resolved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('assigned_to')->references('id')->on('users')->nullOnDelete();
            $table->index('user_id');
            $table->index('status');
            $table->index('priority');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};
