<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carshoopseries', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->bigInteger('serie_id')->nullable();
            $table->bigInteger('carshoop_id')->nullable();

            $table->foreign('serie_id')->on('series')->references('id');
            $table->foreign('carshoop_id')->on('carshoops')->references('id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carshoopseries');
    }
};
