<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAreaAtencionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('area_atencion', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('area_id')->nullable();
            $table->bigInteger('atencion_id')->nullable();
            $table->bigInteger('user_id')->nullable();

            $table->foreign('area_id')->on('areas')->references('id');
            $table->foreign('atencion_id')->on('atencions')->references('id');
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
        Schema::dropIfExists('area_atencion');
    }
}
