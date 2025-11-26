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
        Schema::table('alert_settings', function (Blueprint $table) {
            $table->dropColumn(['offline_threshold', 'outage_threshold']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alert_settings', function (Blueprint $table) {
            $table->integer('offline_threshold')->default(10);
            $table->integer('outage_threshold')->default(3);
        });
    }
};
