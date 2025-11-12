<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('referral_user_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'active', 'inactive'])->default('active');
            $table->integer('referral_level')->default(1);
            $table->decimal('commission_percentage', 5, 2)->default(10);
            $table->decimal('total_earned', 15, 2)->default(0);
            $table->decimal('pending_earnings', 15, 2)->default(0);
            $table->dateTime('referred_at');
            $table->dateTime('activated_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['referrer_id', 'referral_user_id']);
            $table->index('status');
            $table->index('referrer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_referrals');
    }
};
