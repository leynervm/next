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
            $table->bigInteger('atencion_id')->nullable();
            $table->bigInteger('condition_id')->nullable();
            $table->bigInteger('user_id')->nullable();

            $table->foreign('atencion_id')->on('atencions')->references('id');
            $table->foreign('condition_id')->on('conditions')->references('id');
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
        Schema::dropIfExists('atencion_condition');
    }
}
