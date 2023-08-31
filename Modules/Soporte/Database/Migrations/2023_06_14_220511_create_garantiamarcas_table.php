<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGarantiamarcasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('garantiamarcas', function (Blueprint $table) {
            $table->id();
           
            $table->bigInteger('condition_id')->nullable();
            $table->bigInteger('centerservice_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->foreign('condition_id')->on('conditions')->references('id');
            $table->foreign('centerservice_id')->on('centerservices')->references('id');
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
        Schema::dropIfExists('garantiamarcas');
    }
}
