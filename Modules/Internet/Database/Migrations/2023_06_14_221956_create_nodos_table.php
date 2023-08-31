<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNodosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nodos', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('direccion', 255);
            $table->string('longitud', 100)->nullable();
            $table->string('latitud', 100)->nullable();
            $table->integer('delete')->default(0);
            $table->bigInteger('nodotype_id')->nullable();
            $table->bigInteger('centralconexion_id')->nullable();
            $table->bigInteger('ubigeo_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->foreign('nodotype_id')->on('nodotypes')->references('id');
            $table->foreign('centralconexion_id')->on('centralconexions')->references('id');
            $table->foreign('ubigeo_id')->on('ubigeos')->references('id');
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
        Schema::dropIfExists('nodos');
    }
}
