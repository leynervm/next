<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProveedorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proveedors', function (Blueprint $table) {
            $table->id();
            $table->string('document', 11);
            $table->string('name', 255);
            $table->string('direccion', 255);
            $table->string('email', 255)->nullable();
            $table->tinyInteger('proveedortype_id')->nullable();
            $table->bigInteger('ubigeo_id')->nullable();

            $table->foreign('proveedortype_id')->on('proveedortypes')->references('id');
            $table->foreign('ubigeo_id')->on('ubigeos')->references('id');
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
        Schema::dropIfExists('proveedors');
    }
}
