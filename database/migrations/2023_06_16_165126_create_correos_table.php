<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCorreosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('correos', function (Blueprint $table) {
            $table->id();
            $table->string('correo', 255);
            $table->string('delete')->default(0);
            $table->integer('default')->default(0);
            $table->bigInteger('correotype_id')->nullable();
            $table->integer('correable_id');
            $table->string('correable_type', 255);
            $table->foreign('correotype_id')->on('correotypes')->references('id');
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
        Schema::dropIfExists('correos');
    }
}
