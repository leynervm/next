<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompraitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compraitems', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->unsignedDecimal('cantidad', 12, 2);
            $table->unsignedDecimal('price', 18, 2);
            $table->unsignedDecimal('igv', 18, 2);
            $table->unsignedDecimal('descuento', 18, 2);
            $table->unsignedDecimal('subtotaligv', 18, 2);
            $table->unsignedDecimal('subtotaldescuento', 18, 2);
            $table->unsignedDecimal('subtotal', 18, 2);
            $table->unsignedDecimal('total', 18, 2);
            $table->unsignedDecimal('oldprice', 18, 2);
            $table->unsignedDecimal('oldpricesale', 18, 2);
            $table->char('typedescuento', 1)->default(0);
            $table->bigInteger('producto_id')->nullable();
            $table->bigInteger('compra_id')->nullable();
            $table->foreign('producto_id')->on('productos')->references('id');
            $table->foreign('compra_id')->on('compras')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compraitems');
    }
}
