<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Almacen;

class CreateAlmacensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('almacens', function (Blueprint $table) {
            $table->tinyIncrements('id')->unsigned();
            $table->string('name', 255);
            $table->enum('default', [0, Almacen::DEFAULT])->default(0);
            $table->unsignedTinyInteger('sucursal_id');
            $table->foreign('sucursal_id')->on('sucursals')->references('id');
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
        Schema::dropIfExists('almacens');
    }
}
