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
            $table->decimal("amount", 10, 2);
            $table->string("idtransaccion", 255);
            $table->integer("confirmpayment");
            $table->bigInteger("ventaonline_id");
            $table->bigInteger("user_id");
            $table->foreign('ventaonline_id')->on('ventaonlines')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
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
        Schema::dropIfExists('transaccions');
    }
}
