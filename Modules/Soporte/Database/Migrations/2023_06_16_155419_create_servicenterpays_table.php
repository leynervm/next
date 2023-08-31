<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicenterpaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicenterpays', function (Blueprint $table) {
            $table->id();
            $table->integer('day');
            $table->bigInteger('centerservice_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->foreign('centerservice_id')->on('centerservices')->references('id');
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
        Schema::dropIfExists('servicenterpays');
    }
}
