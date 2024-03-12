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
        Schema::create('almacen_sucursal', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('almacen_id');
            $table->unsignedTinyInteger('sucursal_id');
            $table->foreign('almacen_id')->references('id')->on('almacens');
            $table->foreign('sucursal_id')->references('id')->on('sucursals');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('almacen_sucursal');
    }
};
