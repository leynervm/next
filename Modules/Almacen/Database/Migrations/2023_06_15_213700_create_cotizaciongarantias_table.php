<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCotizaciongarantiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cotizaciongarantias', function (Blueprint $table) {
            $table->id();
            $table->integer('time');
            $table->integer('delete')->default(0);
            $table->bigInteger('typegarantia_id')->nullable();
            $table->bigInteger('cotizacion_id')->nullable();
            $table->foreign('typegarantia_id')->on('typegarantias')->references('id');
            $table->foreign('cotizacion_id')->on('cotizacions')->references('id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cotizaciongarantias');
    }
}
