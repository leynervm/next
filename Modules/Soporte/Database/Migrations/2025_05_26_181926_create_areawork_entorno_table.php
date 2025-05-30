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
        Schema::create('areawork_entorno', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('areawork_id');
            $table->unsignedInteger('entorno_id');
            $table->unsignedInteger('user_id');

            $table->foreign('areawork_id')->on('areaworks')->references('id');
            $table->foreign('entorno_id')->on('entornos')->references('id');
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
        Schema::dropIfExists('areawork_entorno');
    }
};
