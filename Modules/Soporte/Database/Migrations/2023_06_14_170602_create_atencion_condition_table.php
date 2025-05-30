<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAtencionConditionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('atencion_condition', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('atencion_id');
            $table->unsignedInteger('condition_id');
            $table->unsignedInteger('user_id');

            $table->foreign('atencion_id')->on('atencions')->references('id');
            $table->foreign('condition_id')->on('conditions')->references('id');
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
        Schema::dropIfExists('atencion_condition');
    }
}
