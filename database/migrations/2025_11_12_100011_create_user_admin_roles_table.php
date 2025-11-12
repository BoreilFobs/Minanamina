<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_admin_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->foreignId('admin_role_id')->constrained('admin_roles')->onDelete('restrict');
            $table->dateTime('assigned_at');
            $table->dateTime('suspended_at')->nullable();
            $table->dateTime('revoked_at')->nullable();
            $table->text('suspension_reason')->nullable();
            $table->timestamps();
            
            $table->index('admin_role_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_admin_roles');
    }
};
