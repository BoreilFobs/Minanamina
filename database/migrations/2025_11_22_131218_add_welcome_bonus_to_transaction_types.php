<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'welcome_bonus' to the transaction type enum
        DB::statement("
            ALTER TABLE user_pieces_transactions 
            MODIFY COLUMN type ENUM(
                'earned',
                'converted',
                'referral_bonus',
                'welcome_bonus',
                'manual_adjustment',
                'reversal'
            ) NOT NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'welcome_bonus' from the transaction type enum
        DB::statement("
            ALTER TABLE user_pieces_transactions 
            MODIFY COLUMN type ENUM(
                'earned',
                'converted',
                'referral_bonus',
                'manual_adjustment',
                'reversal'
            ) NOT NULL
        ");
    }
};
