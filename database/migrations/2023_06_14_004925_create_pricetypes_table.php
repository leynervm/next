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
            $table->char('rounded', 1)->default(0);
            $table->char('decimals', 1)->default(2);
            $table->char('default', 1)->default(0);
            $table->char('web', 1)->default(0);
            $table->char('defaultlogin', 1)->default(0);
            $table->char('temporal', 1)->default(0);
            $table->dateTime('startdate')->nullable();
            $table->dateTime('expiredate')->nullable();
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
