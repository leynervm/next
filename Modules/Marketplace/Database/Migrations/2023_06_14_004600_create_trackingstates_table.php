<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackingstatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trackingstates', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('descripcion', 255)->nullable();
            $table->integer('finish')->default(0);
            $table->string('color', 7);
            $table->integer('default')->default(0);
            $table->integer('delete')->default(0);
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
        Schema::dropIfExists('trackingstates');
    }
}
