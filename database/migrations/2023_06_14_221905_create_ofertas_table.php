<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfertasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ofertas', function (Blueprint $table) {
            $table->id();
            $table->decimal('descuento', 5, 2);
            $table->dateTime('datestart');
            $table->dateTime('dateexpire')->nullable();
            $table->decimal('limit', 8, 2)->nullable();
            $table->decimal('disponible', 8, 2)->nullable();
            $table->decimal('vendidos', 8, 2)->default(0);
            $table->integer('status')->default(0);
            $table->integer('delete')->default(0);
            $table->bigInteger('almacen_id')->nullable();
            $table->bigInteger('producto_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->foreign('almacen_id')->on('almacens')->references('id');
            $table->foreign('producto_id')->on('productos')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
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
        Schema::dropIfExists('ofertas');
    }
}
