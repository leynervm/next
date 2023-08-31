<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventarios', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->decimal('cantidad', 8, 2);
            $table->decimal('precio',  8, 2);
            $table->integer('referencia')->default(0);
            $table->integer('status')->default(0);
            $table->integer('delete')->default(0);
            $table->bigInteger('user_id')->nullable();
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
        Schema::dropIfExists('inventarios');
    }
}
