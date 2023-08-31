<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDigitalizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('digitalizations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->bigInteger('file_id')->nullable();
            $table->bigInteger('network_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->foreign('file_id')->on('files')->references('id');
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
        Schema::dropIfExists('digitalizations');
    }
}
