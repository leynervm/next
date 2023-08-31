<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryloginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historylogins', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->string('idsesion', 255)->nullable();
            $table->string('ip', 255)->nullable();
            $table->string('navegador', 255)->nullable();
            $table->bigInteger('webplataform_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->foreign('webplataform_id')->on('webplataforms')->references('id');
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
        Schema::dropIfExists('historylogins');
    }
}
