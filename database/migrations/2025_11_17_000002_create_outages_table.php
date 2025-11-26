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
        Schema::create('outages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('household_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('device_id');
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->integer('duration_seconds')->nullable();
            $table->unsignedTinyInteger('week_number');
            $table->unsignedSmallInteger('iso_year');
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->timestamps();

            $table->index(['device_id', 'status']);
            $table->index(['iso_year', 'week_number']);
            $table->index('started_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outages');
    }
};

