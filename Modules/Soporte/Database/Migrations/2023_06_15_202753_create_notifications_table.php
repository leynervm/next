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
            $table->string('descripcion', 255);
            $table->bigInteger('order_id')->nullable();
            $table->bigInteger('typenotification_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->foreign('order_id')->on('orders')->references('id');
            $table->foreign('typenotification_id')->on('typenotifications')->references('id');
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
        Schema::dropIfExists('notifications');
    }
}
