<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAtencionEntornoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('atencion_entorno', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('atencion_id')->nullable();
            $table->bigInteger('entorno_id')->nullable();
            $table->bigInteger('user_id')->nullable();

            $table->foreign('atencion_id')->on('atencions')->references('id');
            $table->foreign('entorno_id')->on('entornos')->references('id');
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
        Schema::dropIfExists('atencion_entorno');
    }
}
