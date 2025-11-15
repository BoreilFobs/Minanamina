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
        Schema::table('campaigns', function (Blueprint $table) {
            $table->string('cpa_link')->after('affiliate_link');
            $table->text('validation_rules')->nullable()->after('validation_conditions');
            $table->enum('status', ['draft', 'pending_approval', 'published', 'paused', 'completed'])->default('draft')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn('cpa_link');
            $table->dropColumn('validation_rules');
        });
    }
};
