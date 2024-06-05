<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipmenttypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipmenttypes', function (Blueprint $table) {
            $table->smallIncrements('id')->unsigned();
            $table->string('name', 255);
            $table->string('descripcion', 255)->nullable();
            $table->char('isenvio', 1)->default(0);
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
        Schema::dropIfExists('shipmenttypes');
    }
}
