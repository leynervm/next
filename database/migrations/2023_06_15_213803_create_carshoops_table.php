<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Nwidart\Modules\Facades\Module;

class CreateCarshoopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $moduleName = 'Almacen';

        // if (Module::isEnabled($moduleName)) {
        Schema::create('carshoops', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->decimal('cantidad', 10, 2);
            $table->decimal('pricebuy', 10, 4);
            $table->decimal('price', 10, 4);
            $table->decimal('igv', 10, 4);
            $table->decimal('subtotal', 10, 4);
            $table->decimal('total', 10, 4);
            $table->integer('gratuito')->default(0);
            $table->integer('status')->default(0);
            $table->tinyInteger('almacen_id')->nullable();
            $table->bigInteger('producto_id')->nullable();
            $table->bigInteger('moneda_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->tinyInteger('sucursal_id')->nullable();
            $table->foreign('moneda_id')->on('monedas')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
            $table->foreign('sucursal_id')->on('sucursals')->references('id');
            $table->timestamps();
        });
        // }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carshoops');
    }
}
