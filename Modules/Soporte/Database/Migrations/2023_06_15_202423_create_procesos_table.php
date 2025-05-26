<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcesosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procesos', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->text('descripcion');
            $table->text('confidencial')->nullable();
            $table->char('public', 1)->default(0);
            $table->unsignedInteger('estate_id');
            $table->unsignedInteger('ticket_id');
            $table->unsignedInteger('user_id');
            $table->foreign('estate_id')->on('estates')->references('id');
            $table->foreign('ticket_id')->on('tickets')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
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
        Schema::dropIfExists('procesos');
    }
}
