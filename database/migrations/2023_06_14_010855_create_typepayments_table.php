<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypepaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('typepayments', function (Blueprint $table) {
            $table->tinyIncrements('id')->unsigned();
            $table->string('name', 50);
            $table->char('paycuotas', 1)->default(0);
            $table->char('status', 1)->default(0);
            $table->char('default', 1)->default(0);
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
        Schema::dropIfExists('typepayments');
    }
}
