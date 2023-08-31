<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEspecificacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('especificacions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->integer('delete')->default(0);
            $table->bigInteger('caracteristica_id')->nullable();
            $table->foreign('caracteristica_id')->on('caracteristicas')->references('id');
            $table->timestamps();
            $table->softDeletes();
        });

        // Schema::table('flights', function (Blueprint $table) {
        //     $table->dropSoftDeletes();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('especificacions');
    }
}
