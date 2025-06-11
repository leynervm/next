<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Soporte\Entities\Equipo;

class CreateEquiposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipos', function (Blueprint $table) {
            $table->id();
            $table->string('modelo', 100);
            $table->string('serie', 50)->nullable();
            $table->text('descripcion');
            $table->text('servicio');
            $table->integer('stateinicial')->default(Equipo::ACTIVO);
            $table->integer('statefinal')->default(Equipo::ACTIVO);
            $table->unsignedInteger('typeequipo_id');
            $table->unsignedInteger('marca_id');
            $table->unsignedInteger('ticket_id');
            $table->unsignedInteger('user_id');
            $table->foreign('typeequipo_id')->on('typeequipos')->references('id');
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
        Schema::dropIfExists('equipos');
    }
}
