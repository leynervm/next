<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carshoopitems', function (Blueprint $table) {
            $table->id();
            $table->unsignedDecimal('cantidad', 12, 2);
            $table->unsignedDecimal('pricebuy', 18, 4);
            $table->unsignedDecimal('price', 18, 4);
            $table->unsignedDecimal('igv', 18, 4);
            $table->unsignedDecimal('subtotaligv', 18, 4);
            $table->unsignedDecimal('subtotal', 18, 4);
            $table->unsignedDecimal('total', 18, 4);
            $table->unsignedInteger('itempromo_id')->nullable();
            $table->unsignedBigInteger('producto_id');
            $table->unsignedBigInteger('tvitem_id');
            $table->foreign('itempromo_id')->on('itempromos')->references('id');
            $table->foreign('producto_id')->on('productos')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('tvitem_id')->on('tvitems')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carshoopitems');
    }
};
