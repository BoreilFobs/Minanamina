<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign_participations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('campaign_id')->constrained('campaigns')->onDelete('cascade');
            $table->enum('status', ['active', 'completed', 'rejected', 'abandoned'])->default('active');
            $table->dateTime('started_at');
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('rejected_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->decimal('pieces_earned', 10, 2)->default(0);
            $table->json('validation_data')->nullable();
            $table->integer('time_spent_minutes')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['user_id', 'campaign_id']);
            $table->index('status');
            $table->index('started_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_participations');
    }
};
