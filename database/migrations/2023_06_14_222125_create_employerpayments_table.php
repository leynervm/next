<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployerpaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employerpayments', function (Blueprint $table) {
            $table->id();
            $table->string('month', 7);
            $table->decimal('adelantos', 10, 2)->default(0);
            $table->decimal('descuentos', 10, 2)->default(0);
            $table->decimal('bonus', 10, 2)->default(0);
            $table->decimal('amount', 10, 2);
            $table->unsignedBigInteger('employer_id');
            $table->foreign('employer_id')->on('employers')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employerpayments');
    }
}
