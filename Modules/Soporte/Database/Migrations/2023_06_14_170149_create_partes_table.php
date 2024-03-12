<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partes', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->decimal('cantidad', 8,2);
            $table->string('descripcion', 255);
            $table->string('serie', 32)->nullable();
            $table->string('code', 6);
            $table->decimal('pricebuy', 8, 2);
            $table->decimal('pricesale', 8, 2);
            $table->integer('percent')->default(18);
            $table->integer('condicion')->default(0);
            $table->integer('sendstatus')->default(0);
            $table->string('referencia', 100);
            $table->integer('delete')->default(0);
            $table->bigInteger('marca_id')->nullable();
            $table->bigInteger('unit_id')->nullable();
            $table->bigInteger('user_id')->nullable();

            $table->foreign('marca_id')->on('marcas')->references('id');
            $table->foreign('unit_id')->on('units')->references('id');
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
        Schema::dropIfExists('partes');
    }
}
