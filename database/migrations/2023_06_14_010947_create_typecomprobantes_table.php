<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypecomprobantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('typecomprobantes', function (Blueprint $table) {
            $table->tinyIncrements('id')->unsigned();
            $table->string('code', 2);
            $table->string('name', 100);
            $table->string('descripcion', 100);
            $table->integer('sendsunat')->default(0);
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
        Schema::dropIfExists('typecomprobantes');
    }
}
