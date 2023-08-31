<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCentralconexionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('centralconexions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('direccion', 255);
            $table->string('longitud', 255)->nullable();
            $table->string('latitud', 255)->nullable();
            $table->integer('delete')->default(0);
            $table->bigInteger('ubigeo_id')->nullable();
            $table->bigInteger('conexion_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->foreign('ubigeo_id')->on('ubigeos')->references('id');
            $table->foreign('conexion_id')->on('conexions')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
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
        Schema::dropIfExists('centralconexions');
    }
}
