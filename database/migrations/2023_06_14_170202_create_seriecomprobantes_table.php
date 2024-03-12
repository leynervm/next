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
        Schema::create('seriecomprobantes', function (Blueprint $table) {
            $table->tinyIncrements('id')->unsigned();
            $table->string('serie', 4);
            $table->string('code', 2)->nullable();
            $table->integer('contador')->default(0);
            $table->string('default', 1)->default(0);
            $table->unsignedTinyInteger('typecomprobante_id');
            $table->unsignedTinyInteger('sucursal_id');
            $table->foreign('typecomprobante_id')->on('typecomprobantes')->references('id');
            $table->foreign('sucursal_id')->on('sucursals')->references('id');
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
        Schema::dropIfExists('seriecomprobantes');
    }
};
