<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAtencionEstateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('atencion_estate', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('atencion_id')->nullable();
            $table->bigInteger('estate_id')->nullable();
            $table->bigInteger('user_id')->nullable();

            $table->foreign('atencion_id')->on('atencions')->references('id');
            $table->foreign('estate_id')->on('estates')->references('id');
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
        Schema::dropIfExists('atencion_estate');
    }
}
