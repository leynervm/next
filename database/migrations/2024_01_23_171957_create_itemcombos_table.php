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
        Schema::create('itemcombos', function (Blueprint $table) {
            $table->id();
            $table->unsignedDecimal('descuento', 5, 2)->nullable();
            $table->char('typecombo', 1)->default(0);
            $table->unsignedBigInteger('producto_id');
            $table->unsignedBigInteger('combo_id');
            $table->foreign('producto_id')->references('id')->on('productos');
            $table->foreign('combo_id')->references('id')->on('combos')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('itemcombos');
    }
};
