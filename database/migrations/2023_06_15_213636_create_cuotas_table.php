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
            $table->unsignedTinyInteger('cuota');
            $table->unsignedDecimal('amount', 18, 4);
            $table->date('expiredate');
            $table->unsignedTinyInteger('moneda_id');
            $table->unsignedTinyInteger('sucursal_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('cuotable_id');
            $table->string('cuotable_type');
            $table->foreign('moneda_id')->on('monedas')->references('id');
            $table->foreign('sucursal_id')->on('sucursals')->references('id');
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
