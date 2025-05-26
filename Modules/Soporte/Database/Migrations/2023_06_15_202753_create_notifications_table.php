<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->text('descripcion');
            $table->unsignedInteger('typenotification_id');
            $table->unsignedInteger('user_id');
            $table->integer('notificable_id');
            $table->string('notificable_type', 255);
            $table->foreign('typenotification_id')->on('typenotifications')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
