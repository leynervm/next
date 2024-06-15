<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccesosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accesos', function (Blueprint $table) {
            $table->tinyIncrements('id')->unsigned();
            $table->integer('access')->default(0);
            $table->unsignedTinyInteger('limitsucursals')->default(2)->nullable();
            // $table->integer('adminbusiness')->default(0);
            $table->integer('validatemail')->default(0);
            $table->string('dominio', 255)->nullable();
            $table->integer('descripcion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accesos');
    }
}
