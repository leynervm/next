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
            $table->dateTime("date")->nullable();
            $table->unsignedDecimal("amount", 18, 4)->nullable();
            $table->string("currency", 4);
            $table->string("eci_description", 255)->nullable();
            $table->string("action_description", 255)->nullable();
            $table->string("transaction_id", 255)->nullable();
            $table->string("card")->nullable();
            $table->string("card_type")->nullable();
            $table->string("status")->nullable();
            $table->string("action_code")->nullable();
            $table->string("brand")->nullable();
            $table->string("email", 255)->nullable();
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
