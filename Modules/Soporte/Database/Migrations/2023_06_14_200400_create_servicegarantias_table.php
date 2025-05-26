<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicegarantiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicegarantias', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->dateTime('startperiod');
            $table->dateTime('endperiod');
            $table->unsignedDecimal('igv', 18, 3);
            $table->unsignedDecimal('subtotal', 18, 3);
            $table->unsignedDecimal('total', 18, 3);
            $table->decimal('tipocambio', 5, 2);
            $table->decimal('totaltickets', 8, 2);
            $table->bigInteger('entorno_id')->nullable();
            $table->bigInteger('centerservice_id')->nullable();
            $table->bigInteger('moneda_id');
            $table->bigInteger('user_id');
            $table->foreign('entorno_id')->on('entornos')->references('id');
            $table->foreign('centerservice_id')->on('centerservices')->references('id');
            $table->foreign('moneda_id')->on('monedas')->references('id');
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
        Schema::dropIfExists('servicegarantias');
    }
}
