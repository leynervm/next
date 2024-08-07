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
            $table->integerIncrements('id')->unsigned();
            $table->integer('time');
            $table->unsignedBigInteger('producto_id');
            $table->unsignedTinyInteger('typegarantia_id');
            $table->foreign('producto_id')->on('productos')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('typegarantia_id')->on('typegarantias')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
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
