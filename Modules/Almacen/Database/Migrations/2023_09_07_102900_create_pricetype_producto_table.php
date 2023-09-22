<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePricetypeProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pricetype_producto', function (Blueprint $table) {
            $table->id();
            $table->decimal('price', 10, 4);
            $table->unsignedSmallInteger('pricetype_id')->nullable();
            $table->bigInteger('producto_id')->nullable();
            $table->foreign('pricetype_id')->on('pricetypes')->references('id');
            $table->foreign('producto_id')->on('productos')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pricetype_producto');
    }
}
