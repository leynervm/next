<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaccionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaccions', function (Blueprint $table) {
            $table->id();
            $table->dateTime("date");
            $table->unsignedDecimal("amount", 18, 4);
            $table->string("descripcion", 255);
            $table->string("idtransaccion", 255);
            $table->string("signature", 255);
            $table->string("status");
            $table->string("code");
            $table->string("card");
            $table->string("cardtype");
            $table->string("currency");
            $table->string("idunico");
            $table->string("brand");
            $table->unsignedBigInteger("order_id");
            $table->unsignedBigInteger("user_id");
            $table->foreign('order_id')->on('orders')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaccions');
    }
}
