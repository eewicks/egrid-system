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
        Schema::create('alert_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('alerts_enabled')->default(true);
            $table->json('channels')->nullable(); // ['email', 'sms', 'web_push']
            $table->integer('offline_threshold')->default(10); // minutes
            $table->integer('outage_threshold')->default(3); // per 24 hours
            $table->json('recipients')->nullable(); // array of recipient objects
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alert_settings');
    }
};
