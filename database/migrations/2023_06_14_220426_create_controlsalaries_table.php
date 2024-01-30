<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateControlsalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('controlsalaries', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->decimal('sueldo', 8, 2);
            $table->bigInteger('employer_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->foreign('employer_id')->on('employers')->references('id');
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
        Schema::dropIfExists('controlsalaries');
    }
}
