<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNetworkchanguesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('networkchangues', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->string('detalle', 255);
            $table->bigInteger('networkestate_id')->nullable();
            $table->bigInteger('network_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->foreign('networkestate_id')->on('networkestates')->references('id');
            $table->foreign('network_id')->on('networks')->references('id');
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
        Schema::dropIfExists('networkchangues');
    }
}
