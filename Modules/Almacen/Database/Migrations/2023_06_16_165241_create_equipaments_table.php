<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipamentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipaments', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->decimal('cantidad', 10, 2);
            $table->decimal('price', 10, 2);
            $table->integer('alterstock')->default(0);
            $table->integer('condicion');
            $table->bigInteger('producto_id')->nullable();
            $table->bigInteger('almacen_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->integer('equipable_id');
            $table->string('equipable_type', 255);
            $table->foreign('producto_id')->on('productos')->references('id');
            $table->foreign('almacen_id')->on('almacens')->references('id');
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
        Schema::dropIfExists('equipaments');
    }
}
