<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGarantiaproductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('garantiaproductos', function (Blueprint $table) {
            $table->id();
            $table->integer('time');
            $table->bigInteger('producto_id')->nullable();
            $table->bigInteger('typegarantia_id')->nullable();
            $table->foreign('producto_id')->on('productos')->references('id');
            $table->foreign('typegarantia_id')->on('typegarantias')->references('id');
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
        Schema::dropIfExists('garantiaproductos');
    }
}
