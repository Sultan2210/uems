<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_requests', function (Blueprint $table) {
            if (! Schema::hasColumn('event_requests', 'organizer_name')) {
                $table->string('organizer_name')->nullable()->after('organizer_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('event_requests', function (Blueprint $table) {
            if (Schema::hasColumn('event_requests', 'organizer_name')) {
                $table->dropColumn('organizer_name');
            }
        });
    }
};
