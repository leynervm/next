<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Nwidart\Modules\Facades\Module;

class CreateRequerimentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('requeriments', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->decimal('cantidad', 10, 2);
            $table->string('descripcion', 255);
            $table->string('modelo', 100)->nullable();
            $table->string('ordenproveedor', 24)->nullable();
            $table->integer('status')->default(0);
            $table->unsignedInteger('unit_id');
            $table->unsignedInteger('marca_id')->nullable();
            $table->unsignedInteger('ticket_id');
            $table->unsignedInteger('user_id');
            $table->foreign('unit_id')->on('units')->references('id');
            $table->foreign('marca_id')->on('marcas')->references('id');
            $table->foreign('ticket_id')->on('tickets')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
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
        Schema::dropIfExists('requeriments');
    }
}
