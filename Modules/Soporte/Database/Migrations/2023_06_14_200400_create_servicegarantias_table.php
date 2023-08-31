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
            $table->decimal('price', 8, 2);
            $table->decimal('priceus', 8, 2);
            $table->bigInteger('entorno_id')->nullable();
            $table->bigInteger('marca_id')->nullable();
            $table->bigInteger('condition_id')->nullable();
            $table->bigInteger('moneda_id')->nullable();
            $table->bigInteger('service_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->foreign('entorno_id')->on('entornos')->references('id');
            $table->foreign('marca_id')->on('marcas')->references('id');
            $table->foreign('condition_id')->on('conditions')->references('id');
            $table->foreign('moneda_id')->on('monedas')->references('id');
            $table->foreign('service_id')->on('services')->references('id');
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
        Schema::dropIfExists('servicegarantias');
    }
}
