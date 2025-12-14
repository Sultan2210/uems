<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Add missing columns expected by your Event model/student views
            if (!Schema::hasColumn('events', 'event_name')) $table->string('event_name')->nullable();
            if (!Schema::hasColumn('events', 'location'))   $table->string('location')->nullable();
            if (!Schema::hasColumn('events', 'poster'))     $table->string('poster')->nullable();
            if (!Schema::hasColumn('events', 'created_by')) $table->unsignedBigInteger('created_by')->nullable();
            if (!Schema::hasColumn('events', 'start_at'))   $table->dateTime('start_at')->nullable();
            if (!Schema::hasColumn('events', 'end_at'))     $table->dateTime('end_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // optional rollback
        });
    }
};
