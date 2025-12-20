<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('event_requests', function (Blueprint $table) {
        $table->string('payment_qr_code')->nullable();  // Add nullable column for the QR code file path
    });
}

public function down()
{
    Schema::table('event_requests', function (Blueprint $table) {
        $table->dropColumn('payment_qr_code');  // Remove the column if rollback happens
    });
}

};
