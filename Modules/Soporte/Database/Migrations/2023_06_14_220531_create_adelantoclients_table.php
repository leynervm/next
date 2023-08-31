<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdelantoclientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adelantoclients', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->decimal('amount', 8, 2);
            $table->decimal('saldo', 8, 2);
            $table->integer('status')->default(0);
            $table->integer('delete')->default(0);
            $table->bigInteger('client_id')->nullable();
            $table->bigInteger('cajamovimiento_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->foreign('client_id')->on('clients')->references('id');
            $table->foreign('cajamovimiento_id')->on('cajamovimientos')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
            $table->timestamps();
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
        Schema::dropIfExists('adelantoclients');
    }
}
