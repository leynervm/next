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
            $table->integer('status')->default(0);
            $table->bigInteger('almacen_id')->nullable();
            $table->bigInteger('producto_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            // $table->foreign('producto_id')->on('productos')->references('id');
            // $table->foreign('almacen_id')->on('almacens')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
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
