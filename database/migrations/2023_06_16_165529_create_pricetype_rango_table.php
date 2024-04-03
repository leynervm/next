<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricetypeRangoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pricetype_rango', function (Blueprint $table) {
            $table->id();
            $table->decimal('ganancia', 10, 2);
            $table->unsignedSmallInteger('pricetype_id');
            $table->unsignedSmallInteger('rango_id');
            $table->foreign('pricetype_id')->on('pricetypes')->references('id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('rango_id')->on('rangos')->references('id')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pricetype_rango');
    }
}
