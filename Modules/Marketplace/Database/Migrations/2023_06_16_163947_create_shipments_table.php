<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('direccion', 255);
            $table->string('referencia', 255);
            $table->bigInteger('ubigeo_id')->nullable();
            $table->bigInteger('ventaonline_id')->nullable();
            $table->foreign('ubigeo_id')->on('ubigeos')->references('id');
            $table->foreign('ventaonline_id')->on('ventaonlines')->references('id');
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
        Schema::dropIfExists('shipments');
    }
}
