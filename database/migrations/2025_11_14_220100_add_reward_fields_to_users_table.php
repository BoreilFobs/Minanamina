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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('consecutive_completions')->default(0)->after('pieces_balance');
            $table->integer('total_campaigns_completed')->default(0)->after('consecutive_completions');
            $table->decimal('lifetime_earnings', 15, 2)->default(0)->after('total_campaigns_completed');
            $table->dateTime('last_completion_at')->nullable()->after('lifetime_earnings');
            $table->boolean('is_flagged_suspicious')->default(false)->after('is_admin');
            $table->text('fraud_notes')->nullable()->after('is_flagged_suspicious');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'consecutive_completions',
                'total_campaigns_completed',
                'lifetime_earnings',
                'last_completion_at',
                'is_flagged_suspicious',
                'fraud_notes'
            ]);
        });
    }
};
