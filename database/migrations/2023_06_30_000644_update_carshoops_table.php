<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCarshoopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('carshoops')) {
            return;
        }

        Schema::table('carshoops', function (Blueprint $table) {
            $table->foreign('producto_id')->on('productos')->references('id');
            $table->foreign('almacen_id')->on('almacens')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('carshoops', function (Blueprint $table) {
            $table->dropConstrainedForeignId('producto_id');
            $table->dropConstrainedForeignId('almacen_id');
            // $table->foreign('producto_id')->on('productos')->references('id');
            // $table->foreign('almacen_id')->on('almacens')->references('id');
        });
    }
}
