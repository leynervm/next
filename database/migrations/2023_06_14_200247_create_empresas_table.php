<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('document', 11);
            $table->string('name', 255);
            $table->string('estado', 20);
            $table->string('condicion', 20); 
            $table->string('direccion', 255);
            $table->string('urbanizacion', 255)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('web', 255)->nullable();
            $table->string('logo', 100)->nullable();
            $table->string('icono', 100)->nullable();
            $table->string('privatekey', 100)->nullable();
            $table->string('publickey', 100)->nullable();
            $table->string('usuariosol', 32)->nullable();
            $table->string('clavesol', 32)->nullable();
            $table->integer('uselistprice')->default(0);
            $table->integer('default')->default(1);
            $table->integer('delete')->default(0);
            $table->bigInteger('ubigeo_id')->nullable();
            $table->bigInteger('user_id')->nullable();
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
        Schema::dropIfExists('empresas');
    }
}
