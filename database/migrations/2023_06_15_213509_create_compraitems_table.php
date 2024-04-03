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
            $table->id();
            $table->decimal('oldstock', 12, 2);
            $table->decimal('oldpricebuy', 18, 2);
            $table->decimal('oldpricesale', 18, 2);
            $table->decimal('cantidad', 12, 2);
            $table->decimal('pricebuy', 18, 4);
            $table->decimal('igv', 18, 4);
            $table->decimal('descuento', 18, 4);
            $table->decimal('subtotal', 18, 4);
            $table->decimal('total', 18, 4);
            $table->bigInteger('producto_id')->nullable();
            $table->bigInteger('almacen_id')->nullable();
            $table->bigInteger('compra_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->foreign('producto_id')->on('productos')->references('id');
            $table->foreign('almacen_id')->on('almacens')->references('id');
            $table->foreign('compra_id')->on('compras')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
            $table->timestamps();
            $table->softDeletes();
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
