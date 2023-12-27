<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleproductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalleproductos', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('descripcion');
            $table->bigInteger('producto_id')->nullable();
            $table->foreign('producto_id')->on('productos')->references('id');
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
        Schema::dropIfExists('detalleproductos');
    }
}
