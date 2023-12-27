<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypegarantiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('typegarantias', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('timestring', 20);
            $table->integer('time');
            $table->string('datecode', 2);
            $table->integer('delete')->default(0);
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
        Schema::dropIfExists('typegarantias');
    }
}
