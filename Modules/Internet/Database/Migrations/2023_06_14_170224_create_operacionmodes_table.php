<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperacionmodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operacionmodes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->integer('delete')->default(0);
            $table->bigInteger('categoryequipament_id')->nullable();
            $table->bigInteger('user_id')->nullable();

            $table->foreign('categoryequipament_id')->on('categoryequipaments')->references('id');
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
        Schema::dropIfExists('operacionmodes');
    }
}
