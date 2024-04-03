<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employers', function (Blueprint $table) {
            $table->id();
            $table->string('document', 11);
            $table->string('name', 255);
            $table->date('nacimiento');
            $table->string('sexo', 1);
            $table->decimal('sueldo', 12, 2);
            $table->unsignedSmallInteger('turno_id')->nullable();
            $table->unsignedTinyInteger('areawork_id')->nullable();
            $table->unsignedBigInteger('sucursal_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('turno_id')->on('turnos')->references('id');
            $table->foreign('areawork_id')->on('areaworks')->references('id');
            $table->foreign('sucursal_id')->on('sucursals')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
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
        Schema::dropIfExists('employers');
    }
}
