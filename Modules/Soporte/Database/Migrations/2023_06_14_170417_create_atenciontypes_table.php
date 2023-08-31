<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAtenciontypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('atenciontypes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->integer('delete')->default(0);
            $table->bigInteger('atencion_id')->nullable();
            $table->bigInteger('user_id')->nullable();

            $table->foreign('atencion_id')->on('atencions')->references('id');
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
        Schema::dropIfExists('atenciontypes');
    }
}
