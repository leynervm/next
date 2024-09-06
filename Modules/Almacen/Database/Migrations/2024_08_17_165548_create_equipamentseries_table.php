<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipamentseriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipamentseries', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->integer('delete')->default(0);
            $table->bigInteger('serie_id')->nullable();
            $table->bigInteger('equipament_id')->nullable();
            $table->foreign('serie_id')->on('series')->references('id');
            $table->foreign('equipament_id')->on('equipaments')->references('id');
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
        Schema::dropIfExists('equipamentseries');
    }
}
