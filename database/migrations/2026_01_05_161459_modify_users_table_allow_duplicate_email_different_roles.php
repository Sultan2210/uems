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
            // Drop the unique constraint on email
            $table->dropUnique(['email']);
        });

        Schema::table('users', function (Blueprint $table) {
            // Add composite unique constraint on email and role
            // This allows same email for different roles
            $table->unique(['email', 'role'], 'users_email_role_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the composite unique constraint
            $table->dropUnique('users_email_role_unique');
        });

        Schema::table('users', function (Blueprint $table) {
            // Restore the unique constraint on email
            $table->unique('email');
        });
    }
};
