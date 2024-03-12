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
        Schema::create('monthboxes', function (Blueprint $table) {
            $table->id();
            $table->string('month', 7);
            $table->string('name', 255);
            $table->dateTime('startdate');
            $table->dateTime('expiredate');
            $table->char('status', 1)->default(0);
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
        Schema::dropIfExists('monthboxes');
    }
};
