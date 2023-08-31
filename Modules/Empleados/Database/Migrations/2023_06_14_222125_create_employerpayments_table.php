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
            $table->dateTime('date');
            $table->decimal('adelanto', 8, 2);
            $table->decimal('descuento', 8, 2)->default(0);
            $table->decimal('saldo', 8, 2);
            $table->string('datemonth', 7);
            $table->integer('delete')->default(0);
            $table->bigInteger('employer_id')->nullable();
            $table->bigInteger('cajamovimiento_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->foreign('employer_id')->on('employers')->references('id');
            $table->foreign('cajamovimiento_id')->on('cajamovimientos')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
            $table->timestamps();
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
