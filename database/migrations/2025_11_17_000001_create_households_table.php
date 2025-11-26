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
        Schema::create('households', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_pk')
                ->nullable()
                ->constrained('devices')
                ->nullOnDelete();
            $table->string('device_id')->unique();
            $table->string('name');
            $table->string('location')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('last_status')->default('OFF');
            $table->timestamp('last_seen')->nullable();
            $table->timestamps();

            $table->index(['last_status', 'last_seen']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('households');
    }
};

