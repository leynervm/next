<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderbuysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orderbuys', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->integer('code');
            $table->decimal('gravado', 8, 2);
            $table->decimal('exonerado', 8, 2);
            $table->decimal('igv', 8, 2);
            $table->decimal('total', 8, 2);
            $table->decimal('pricedolar', 8, 2);
            $table->integer('status')->default(0);
            $table->bigInteger('moneda_id')->nullable();
            $table->bigInteger('proveedor_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->foreign('moneda_id')->on('monedas')->references('id');
            $table->foreign('proveedor_id')->on('proveedors')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
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
        Schema::dropIfExists('orderbuys');
    }
}
