<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuotas', function (Blueprint $table) {
            $table->id();
            $table->integer('cuota');
            $table->decimal('amount', 10, 2);
            $table->date('expiredate');
            $table->dateTime('datepayment')->nullable();
            $table->integer('delete')->default(0);
            $table->bigInteger('venta_id')->nullable();
            $table->bigInteger('cajamovimiento_id')->nullable();
            $table->bigInteger('userpayment_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->foreign('venta_id')->on('ventas')->references('id');
            $table->foreign('cajamovimiento_id')->on('cajamovimientos')->references('id');
            $table->foreign('userpayment_id')->on('users')->references('id');
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
        Schema::dropIfExists('cuotas');
    }
}
