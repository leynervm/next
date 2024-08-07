<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEspecificacionProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('especificacion_producto', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('orden');
            $table->bigInteger('especificacion_id')->nullable();
            $table->bigInteger('producto_id')->nullable();
            $table->foreign('especificacion_id')->on('especificacions')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('producto_id')->on('productos')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('especificacion_producto');
    }
}
