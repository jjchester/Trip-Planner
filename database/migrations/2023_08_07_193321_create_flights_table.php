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
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('airline_id');
            $table->string('flight_number');
            $table->unsignedBigInteger('departure_airport_id');
            $table->unsignedBigInteger('arrival_airport_id');
            $table->time('departure_time');
            $table->unsignedInteger('duration_minutes');
            $table->float('price');
            $table->timestamps();

            $table->foreign('airline_id')->references('id')->on('airlines');
            $table->foreign('departure_airport_id')->references('id')->on('airports');
            $table->foreign('arrival_airport_id')->references('id')->on('airports');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
