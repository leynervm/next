<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderdireccionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orderdireccions', function (Blueprint $table) {
            $table->id();
            $table->dateTime('visita');
            $table->string('direccion', 255);
            $table->string('referencia', 255);
            $table->bigInteger('ubigeo_id')->nullable();
            $table->bigInteger('order_id')->nullable();
            $table->foreign('ubigeo_id')->on('ubigeos')->references('id');
            $table->foreign('order_id')->on('orders')->references('id');
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
        Schema::dropIfExists('orderdireccions');
    }
}
