<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Soporte\Entities\Repair;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repairs', function (Blueprint $table) {
            $table->id();
            $table->string('modelo', 100);
            $table->string('serie', 50)->nullable();
            $table->text('descripcion')->nullable();
            $table->integer('stateinicial')->default(Repair::ACTIVO);
            $table->integer('statefinal')->default(Repair::ACTIVO);
            $table->bigInteger('equipo_id')->nullable();
            $table->bigInteger('marca_id')->nullable();
            $table->bigInteger('ticket_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->foreign('equipo_id')->on('equipos')->references('id');
            $table->foreign('marca_id')->on('marcas')->references('id');
            $table->foreign('ticket_id')->on('tickets')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('repairs');
    }
};
