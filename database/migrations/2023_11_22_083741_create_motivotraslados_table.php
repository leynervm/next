<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motivotraslados', function (Blueprint $table) {
            $table->tinyIncrements('id')->unsigned();
            $table->string('code', 2)->nullable();
            $table->string('name', 255);
            $table->unsignedTinyInteger('typecomprobante_id')->nullable();
            $table->foreign('typecomprobante_id')->references('id')->on('typecomprobantes');
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
        Schema::dropIfExists('motivotraslados');
    }
};
