<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDerivadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('derivados', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->dateTime('datedevolucion')->nullable();
            $table->decimal('total', 10, 2)->nullable();
            $table->integer('confirmcosto')->default(0);
            $table->integer('transportuno')->default(0);
            $table->integer('transportdos')->default(0);
            $table->integer('delete')->default(0);
            $table->bigInteger('userdevolucion_id')->nullable();
            $table->bigInteger('centerservice_id')->nullable();
            $table->bigInteger('employer_id')->nullable();
            $table->bigInteger('orderequipo_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->foreign('userdevolucion_id')->on('users')->references('id');
            $table->foreign('centerservice_id')->on('centerservices')->references('id');
            // $table->foreign('employer_id')->on('employers')->references('id');
            $table->foreign('orderequipo_id')->on('orderequipos')->references('id');
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
        Schema::dropIfExists('derivados');
    }
}
