<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('busines', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->string('document', 11);
            $table->string('name', 255); 
            $table->string('direccion', 255);
            $table->string('email', 100)->nullable();
            $table->string('web', 100)->nullable();
            $table->string('driver', 10);
            $table->dateTime('port', 5);
            $table->string('host', 15);
            $table->string('database', 50);
            $table->string('user', 50);
            $table->string('password', 100);
            $table->integer('default')->default(0);
            $table->integer('delete')->default(0);
            $table->bigInteger('ubigeo_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->foreign('ubigeo_id')->on('ubigeos')->references('id');
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
        Schema::dropIfExists('busines');
    }
}
