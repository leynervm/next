<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCenterservicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('centerservices', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->string('document', 11);
            $table->string('name', 255);
            $table->string('direccion', 255);
            $table->string('email', 255)->nullable();
            $table->integer('delete')->default(0);
            $table->bigInteger('marca_id')->nullable();
            $table->bigInteger('ubigeo_id')->nullable();
            $table->bigInteger('moneda_id')->nullable();
            $table->bigInteger('condition_id')->nullable();
            $table->bigInteger('user_id')->nullable();

            $table->foreign('marca_id')->on('marcas')->references('id');
            $table->foreign('ubigeo_id')->on('ubigeos')->references('id');
            $table->foreign('moneda_id')->on('monedas')->references('id');
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
        Schema::dropIfExists('centerservices');
    }
}
