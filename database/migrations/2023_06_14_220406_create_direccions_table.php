<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDireccionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('direccions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->char('default', 1)->default(0);
            $table->bigInteger('ubigeo_id')->nullable();
            $table->integer('direccionable_id');
            $table->string('direccionable_type');
            $table->foreign('ubigeo_id')->on('ubigeos')->references('id');
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
        Schema::dropIfExists('direccions');
    }
}
