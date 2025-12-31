<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'admin_approval_status')) {
                $table->enum('admin_approval_status', ['pending', 'approved', 'rejected'])->nullable()->after('picture');
            }
        });

        // Set existing admins to approved status
        DB::table('users')
            ->where('role', 'admin')
            ->whereNull('admin_approval_status')
            ->update(['admin_approval_status' => 'approved']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'admin_approval_status')) {
                $table->dropColumn('admin_approval_status');
            }
        });
    }
};
