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
        Schema::create('transportvehiculos', function (Blueprint $table) {
            $table->id();
            $table->string('placa', 8);
            $table->string('tarjeta', 15)->nullable();
            $table->string('autorizacion', 50)->nullable();
            $table->integer('principal')->default(0);
            $table->bigInteger('guia_id');
            $table->foreign('guia_id')->on('guias')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transportvehiculos');
    }
};
