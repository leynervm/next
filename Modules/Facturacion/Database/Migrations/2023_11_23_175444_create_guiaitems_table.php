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
        Schema::create('guiaitems', function (Blueprint $table) {
            $table->id();
            $table->integer('item');
            $table->integer('linereference');
            $table->decimal('cantidad', 10, 2);
            $table->smallInteger('alterstock')->default(0);
            $table->bigInteger('producto_id');
            $table->tinyInteger('almacen_id');
            $table->bigInteger('guia_id');
            $table->foreign('producto_id')->references('id')->on('productos');
            $table->foreign('almacen_id')->references('id')->on('almacens');
            $table->foreign('guia_id')->references('id')->on('guias');
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
        Schema::dropIfExists('guiaitems');
    }
};
