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
        Schema::create('kardexes', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->decimal('cantidad', 12, 4);
            $table->decimal('oldstock', 12, 4);
            $table->decimal('newstock', 12, 4);
            $table->char('simbolo', 1)->nullable();
            $table->string('detalle', 255);
            $table->string('reference', 255)->nullable();
            $table->unsignedBigInteger('producto_id');
            $table->unsignedTinyInteger('almacen_id');
            $table->unsignedTinyInteger('sucursal_id')->nullable();
            $table->unsignedBigInteger('user_id');
            // $table->unsignedBigInteger('promocion_id')->nullable();
            $table->foreign('producto_id')->on('productos')->references('id');
            $table->foreign('almacen_id')->on('almacens')->references('id');
            $table->foreign('sucursal_id')->on('sucursals')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
            // $table->foreign('promocion_id')->on('promocions')->references('id');
            $table->integer('kardeable_id');
            $table->string('kardeable_type', 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kardexes');
    }
};
