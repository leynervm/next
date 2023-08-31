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
            $table->decimal('stockactual', 10, 2);
            $table->decimal('cantidad', 10, 2);
            $table->decimal('pricebuy', 10, 2);
            $table->decimal('priceusbuy', 10, 2)->nullable();
            $table->decimal('igv', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->integer('delete')->default(0);
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
