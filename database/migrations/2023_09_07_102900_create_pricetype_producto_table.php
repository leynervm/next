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
            $table->decimal('price', 18, 4);
            $table->unsignedSmallInteger('pricetype_id');
            $table->bigInteger('producto_id');
            $table->foreign('pricetype_id')->on('pricetypes')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('pricetype_producto');
    }
}
