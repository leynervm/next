<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trackings', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->string('descripcion', 255);
            $table->bigInteger('estate_id')->nullable();
            $table->bigInteger('ventaonline_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->foreign('estate_id')->on('estates')->references('id');
            $table->foreign('ventaonline_id')->on('ventaonlines')->references('id');
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
        Schema::dropIfExists('trackings');
    }
}
