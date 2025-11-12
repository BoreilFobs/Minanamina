<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('image')->nullable();
            $table->string('affiliate_link');
            $table->decimal('pieces_reward', 10, 2);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->text('geographic_restrictions')->nullable();
            $table->text('validation_conditions')->nullable();
            $table->enum('status', ['draft', 'pending_approval', 'published', 'archived'])->default('draft');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->integer('max_participants')->nullable();
            $table->integer('current_participants')->default(0);
            $table->decimal('total_rewards_distributed', 15, 2)->default(0);
            $table->decimal('conversion_rate', 5, 2)->default(100);
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
            $table->index('status');
            $table->index('start_date');
            $table->index('end_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
