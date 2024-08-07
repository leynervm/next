<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlmacenProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('almacen_producto', function (Blueprint $table) {
            $table->id();
            $table->decimal('cantidad', 10, 2);
            $table->bigInteger('producto_id')->nullable();
            $table->bigInteger('almacen_id')->nullable();
            $table->foreign('producto_id')->on('productos')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('almacen_id')->on('almacens')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('almacen_producto');
    }
}
