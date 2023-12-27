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
        Schema::create('sucursals', function (Blueprint $table) {
            $table->tinyIncrements('id')->unsigned();
            $table->string('name', 255);
            $table->string('direccion', 255);
            $table->string('codeanexo', 4);
            $table->integer('status')->default(0);
            $table->integer('default')->default(0);
            $table->unsignedSmallInteger('ubigeo_id')->nullable();
            $table->unsignedTinyInteger('empresa_id')->nullable();
            
            $table->foreign('ubigeo_id')->on('ubigeos')->references('id');
            $table->foreign('empresa_id')->on('empresas')->references('id');
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
        Schema::dropIfExists('sucursals');
    }
};
