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
            $table->bigInteger('pricetype_id')->nullable();
            $table->bigInteger('rango_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->foreign('pricetype_id')->on('pricetypes')->references('id');
            $table->foreign('rango_id')->on('rangos')->references('id');
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
        Schema::dropIfExists('pricetype_rango');
    }
}
