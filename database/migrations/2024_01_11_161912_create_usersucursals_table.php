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
        Schema::create('usersucursals', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('default')->default(0);
            $table->tinyInteger('almacen_id')->nullable();
            $table->tinyInteger('sucursal_id');
            $table->bigInteger('user_id');
            $table->foreign('almacen_id')->on('almacens')->references('id');
            $table->foreign('sucursal_id')->on('sucursals')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usersucursals');
    }
};
