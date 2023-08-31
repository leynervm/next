<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNetworkequipamentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('networkequipaments', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('modelo', 50);
            $table->string('serie', 50)->nullable();
            $table->decimal('cantidad', 10, 2);
            $table->decimal('price', 10, 2);
            $table->decimal('importe', 10, 2);
            $table->integer('delete')->default(0);
            $table->bigInteger('marca_id')->nullable();
            $table->bigInteger('network_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->foreign('marca_id')->on('marcas')->references('id');
            $table->foreign('network_id')->on('networks')->references('id');
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
        Schema::dropIfExists('networkequipaments');
    }
}
