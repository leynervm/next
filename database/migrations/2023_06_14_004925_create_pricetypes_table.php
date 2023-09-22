<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricetypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pricetypes', function (Blueprint $table) {
            $table->smallIncrements('id')->unsigned();
            $table->string('name', 255);
            $table->decimal('ganancia', 8, 2);
            $table->char('decimalrounded', 1)->default(0);
            $table->char('decimals', 1)->default(2);
            $table->integer('default')->default(0);
            $table->integer('web')->default(0);
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
        Schema::dropIfExists('pricetypes');
    }
}
