<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promocions', function (Blueprint $table) {
            $table->id();
            $table->unsignedDecimal('pricebuy', 18, 4);
            $table->char('type', 1)->default(0);
            $table->unsignedDecimal('limit', 10, 2)->nullable();
            $table->unsignedDecimal('descuento', 5, 2)->nullable();
            $table->unsignedDecimal('outs', 10, 2)->default(0);
            $table->dateTime('startdate')->nullable();
            $table->dateTime('expiredate')->nullable();
            $table->smallInteger('status')->default(0);
            $table->unsignedBigInteger('producto_id');
            $table->foreign('producto_id')->references('id')->on('productos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promocions');
    }
};
