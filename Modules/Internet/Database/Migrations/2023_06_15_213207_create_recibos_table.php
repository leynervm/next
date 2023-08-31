<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecibosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recibos', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->integer('code');
            $table->date('expire');
            $table->string('datemonth', 7);
            $table->decimal('price', 10, 2);
            $table->decimal('descuento', 10, 2);
            $table->decimal('reconeccion', 10, 2);
            $table->decimal('total', 10, 2);
            $table->string('leyenda', 255);
            $table->integer('delete')->default(0);
            $table->bigInteger('moneda_id')->nullable();
            $table->bigInteger('tribute_id')->nullable();
            $table->bigInteger('network_id')->nullable();
            $table->bigInteger('cajamovimiento_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->foreign('moneda_id')->on('monedas')->references('id');
            $table->foreign('tribute_id')->on('tributes')->references('id');
            $table->foreign('network_id')->on('networks')->references('id');
            $table->foreign('cajamovimiento_id')->on('cajamovimientos')->references('id');
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
        Schema::dropIfExists('recibos');
    }
}
