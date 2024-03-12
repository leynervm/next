<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->text('name');
            $table->decimal('download', 5, 2);
            $table->decimal('upload', 5, 2);
            $table->decimal('disponibilidad', 5, 2);
            $table->decimal('tasagarantizada', 5, 2);
            $table->decimal('price', 8, 2);
            $table->bigInteger('typenetwork_id')->nullable();
            $table->bigInteger('zona_id')->nullable();
            $table->bigInteger('unit_id')->nullable();
            $table->bigInteger('user_id')->nullable();

            $table->foreign('typenetwork_id')->on('typenetworks')->references('id');
            $table->foreign('zona_id')->on('zonas')->references('id');
            $table->foreign('unit_id')->on('units')->references('id');
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
        Schema::dropIfExists('plans');
    }
}
