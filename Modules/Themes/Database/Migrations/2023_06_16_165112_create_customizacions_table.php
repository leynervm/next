<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomizacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customizacions', function (Blueprint $table) {
            $table->id();
            $table->string('imagebackground', 100)->nullable();
            $table->integer('opacity')->nullable();
            $table->text('icon')->nullable();
            $table->bigInteger('theme_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->foreign('user_id')->on('users')->references('id');
            $table->foreign('theme_id')->on('themes')->references('id');
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
        Schema::dropIfExists('customizacions');
    }
}
