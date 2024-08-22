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
        Schema::create('almacencompras', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->unsignedDecimal('cantidad', 12, 2);
            $table->bigInteger('almacen_id');
            $table->bigInteger('compraitem_id');
            $table->foreign('almacen_id')->on('almacens')->references('id');
            $table->foreign('compraitem_id')->on('compraitems')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('almacencompras');
    }
};
