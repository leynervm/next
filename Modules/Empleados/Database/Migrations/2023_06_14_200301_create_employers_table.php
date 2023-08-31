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
            $table->dateTime('date');
            $table->string('document', 8);
            $table->string('name', 255); 
            $table->string('direccion', 255);
            $table->date('nacimiento', 255)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('sueldo', 255)->nullable();
            $table->dateTime('datedown')->nullable();
            $table->integer('delete')->default(0);
            $table->bigInteger('area_id')->nullable();
            $table->bigInteger('ubigeo_id')->nullable();
            $table->bigInteger('cargo_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->foreign('area_id')->on('areas')->references('id');
            $table->foreign('ubigeo_id')->on('ubigeos')->references('id');
            $table->foreign('cargo_id')->on('cargos')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
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
        Schema::dropIfExists('employers');
    }
}
