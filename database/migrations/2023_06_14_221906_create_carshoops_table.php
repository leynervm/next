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
            $table->bigIncrements('id')->unsigned();
            $table->dateTime('date');
            $table->decimal('cantidad', 10, 2);
            $table->decimal('pricebuy', 10, 4);
            $table->decimal('price', 10, 4);
            $table->decimal('igv', 10, 4);
            $table->decimal('subtotal', 10, 4);
            $table->decimal('total', 10, 4);
            $table->char('gratuito', 1)->default(0);
            $table->char('status', 1)->default(0);
            $table->char('mode', 1)->default(0);
            $table->unsignedBigInteger('promocion_id')->nullable();
            $table->unsignedBigInteger('producto_id');
            $table->unsignedTinyInteger('almacen_id');
            $table->unsignedTinyInteger('moneda_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedTinyInteger('sucursal_id');
            $table->unsignedBigInteger('cartable_id')->nullable();
            $table->string('cartable_type');
            $table->foreign('promocion_id')->on('promocions')->references('id');
            $table->foreign('producto_id')->on('productos')->references('id');
            $table->foreign('almacen_id')->on('almacens')->references('id');
            $table->foreign('moneda_id')->on('monedas')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
            $table->foreign('sucursal_id')->on('sucursals')->references('id');
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
