<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDerivadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('derivados', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->dateTime('datedevolucion')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->bigInteger('equipo_id');
            $table->bigInteger('centerservice_id');
            $table->bigInteger('user_id');
            $table->bigInteger('userdevolucion_id')->nullable();
            $table->foreign('equipo_id')->on('equipos')->references('id');
            $table->foreign('centerservice_id')->on('centerservices')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
            $table->foreign('userdevolucion_id')->on('users')->references('id');
            // $table->bigInteger('employer_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('derivados');
    }
}
