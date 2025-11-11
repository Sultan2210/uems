<?php

use Illuminate\Database\Migrations\Migration;
// database/migrations/xxxx_xx_xx_xxxxxx_create_registrations_table.php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends \Illuminate\Database\Migrations\Migration {
    public function up(): void {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // optional extra details captured during registration
            $table->string('full_name')->nullable();
            $table->string('matric_or_staff_no')->nullable();
            $table->string('department')->nullable();

            $table->boolean('attended')->default(false);
            $table->timestamp('registered_at')->useCurrent();

            $table->timestamps();

            $table->unique(['event_id','user_id']); // one registration per user per event
        });
    }

    public function down(): void {
        Schema::dropIfExists('registrations');
    }
};
