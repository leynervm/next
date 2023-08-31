<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderequiposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orderequipos', function (Blueprint $table) {
            $table->id();
            $table->string('modelo', 100);
            $table->string('serie', 50)->nullable();
            $table->text('descripcion');
            $table->integer('stateinicial')->default(0);
            $table->integer('statefinal')->default(0);
            $table->bigInteger('equipo_id')->nullable();
            $table->bigInteger('marca_id')->nullable();
            $table->bigInteger('order_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->foreign('equipo_id')->on('equipos')->references('id');
            $table->foreign('marca_id')->on('marcas')->references('id');
            $table->foreign('order_id')->on('orders')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orderequipos');
    }
}
