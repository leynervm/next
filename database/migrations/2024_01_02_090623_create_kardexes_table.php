<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
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
            $table->bigInteger('producto_id');
            $table->tinyInteger('almacen_id');
            $table->tinyInteger('sucursal_id');
            $table->bigInteger('user_id')->nullable();
            $table->foreign('producto_id')->on('productos')->references('id');
            $table->foreign('almacen_id')->on('almacens')->references('id');
            $table->foreign('sucursal_id')->on('sucursals')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
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
