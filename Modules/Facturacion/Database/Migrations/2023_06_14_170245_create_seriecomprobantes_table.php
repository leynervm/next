<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeriecomprobantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seriecomprobantes', function (Blueprint $table) {
            $table->id();
            $table->string('serie', 4);
            $table->integer('default')->default(0);
            $table->integer('delete')->default(0);
            $table->bigInteger('typecomprobante_id')->nullable();
            $table->bigInteger('user_id')->nullable();

            $table->foreign('typecomprobante_id')->on('typecomprobantes')->references('id');
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
        Schema::dropIfExists('seriecomprobantes');
    }
}
