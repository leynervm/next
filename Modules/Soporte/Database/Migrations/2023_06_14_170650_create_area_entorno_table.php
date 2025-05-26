<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAreaEntornoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('area_entorno', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('area_id')->nullable();
            $table->bigInteger('entorno_id')->nullable();
            $table->bigInteger('user_id')->nullable();

            $table->foreign('area_id')->on('areas')->references('id');
            $table->foreign('entorno_id')->on('entornos')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('area_entorno');
    }
}
