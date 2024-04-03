<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carshoopitems', function (Blueprint $table) {
            $table->id();
            $table->unsignedDecimal('pricebuy', 18, 4);
            $table->unsignedDecimal('price', 18, 4);
            $table->char('requireserie', 1)->default(0);
            $table->unsignedBigInteger('producto_id');
            $table->unsignedBigInteger('carshoop_id');
            $table->foreign('producto_id')->on('productos')->references('id');
            $table->foreign('carshoop_id')->on('carshoops')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carshoopitems');
    }
};
