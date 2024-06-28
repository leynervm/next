<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMethodpaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('methodpayments', function (Blueprint $table) {
            $table->tinyIncrements('id')->unsigned();
            $table->string('name', 100);
            $table->unsignedTinyInteger('type')->default(0);
            $table->unsignedTinyInteger('default')->default(0);
            $table->unsignedTinyInteger('definido')->default(0);
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
        Schema::dropIfExists('methodpayments');
    }
}
