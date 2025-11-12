<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leaderboards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['earnings', 'referrals', 'badges', 'campaigns'])->default('earnings');
            $table->integer('rank')->default(0);
            $table->decimal('score', 15, 2)->default(0);
            $table->enum('period', ['daily', 'weekly', 'monthly', 'all_time'])->default('all_time');
            $table->date('period_date')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'type', 'period', 'period_date']);
            $table->index('type');
            $table->index('period');
            $table->index('rank');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leaderboards');
    }
};
