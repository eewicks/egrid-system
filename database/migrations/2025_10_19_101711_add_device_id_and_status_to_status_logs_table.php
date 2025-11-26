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
        Schema::table('status_logs', function (Blueprint $table) {
            $table->string('device_id')->after('id');
            $table->string('status')->after('device_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('status_logs', function (Blueprint $table) {
            $table->dropColumn(['device_id', 'status']);
        });
    }
};
