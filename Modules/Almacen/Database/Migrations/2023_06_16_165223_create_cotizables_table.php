<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCotizablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cotizables', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->integer('status')->default(0);
            $table->bigInteger('cotizacion_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->integer('cotizable_id');
            $table->string('cotizable_type', 255);
            $table->foreign('cotizacion_id')->on('cotizacions')->references('id');
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
        Schema::dropIfExists('cotizables');
    }
}
