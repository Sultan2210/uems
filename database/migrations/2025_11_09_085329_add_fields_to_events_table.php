<?php

use Illuminate\Database\Migrations\Migration;
// database/migrations/xxxx_xx_xx_xxxxxx_add_fields_to_events_table.php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends \Illuminate\Database\Migrations\Migration {
    public function up(): void {
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            }
            if (!Schema::hasColumn('events', 'start_at')) {
                $table->dateTime('start_at')->nullable();
            }
            if (!Schema::hasColumn('events', 'end_at')) {
                $table->dateTime('end_at')->nullable();
            }
            if (!Schema::hasColumn('events', 'capacity')) {
                $table->unsignedInteger('capacity')->nullable();
            }
        });
    }

    public function down(): void {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'status'))   $table->dropColumn('status');
            if (Schema::hasColumn('events', 'start_at')) $table->dropColumn('start_at');
            if (Schema::hasColumn('events', 'end_at'))   $table->dropColumn('end_at');
            if (Schema::hasColumn('events', 'capacity')) $table->dropColumn('capacity');
        });
    }
};
