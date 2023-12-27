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
        Schema::create('seriecomprobante_sucursal', function (Blueprint $table) {
            $table->id();
            $table->integer('default')->default(0);
            $table->unsignedTinyInteger('seriecomprobante_id')->nullable();
            $table->unsignedTinyInteger('sucursal_id')->nullable();
            $table->foreign('seriecomprobante_id')->on('seriecomprobantes')->references('id');
            $table->foreign('sucursal_id')->on('sucursals')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seriecomprobante_sucursal');
    }
};
