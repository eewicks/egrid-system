<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('outages', function (Blueprint $table) {
            $table->unsignedBigInteger('household_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('outages', function (Blueprint $table) {
            $table->unsignedBigInteger('household_id')->nullable(false)->change();
        });
    }
};
