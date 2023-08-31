<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNetworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('networks', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->integer('code');
            $table->string('direccion', 255);
            $table->string('referencia', 255);
            $table->decimal('price', 8, 2);
            $table->string('namemikrotik', 100)->nullable();
            $table->string('ip', 24)->nullable();
            $table->string('mac', 100)->nullable();
            $table->integer('statuspay')->default(0);
            $table->dateTime('lastpay')->nullable();
            $table->string('longitud', 100)->nullable();
            $table->string('latitud', 100)->nullable();
            $table->integer('delete')->default(0);
            $table->bigInteger('condicionpayment_id')->nullable();
            $table->bigInteger('networkestate_id')->nullable();
            $table->bigInteger('plan_id')->nullable();
            $table->bigInteger('client_id')->nullable();
            $table->bigInteger('ciclo_id')->nullable();
            $table->bigInteger('ubigeo_id')->nullable();
            $table->bigInteger('user_id')->nullable();

            $table->foreign('condicionpayment_id')->on('condicionpayments')->references('id');
            $table->foreign('networkestate_id')->on('networkestates')->references('id');
            $table->foreign('plan_id')->on('plans')->references('id');
            $table->foreign('client_id')->on('clients')->references('id');
            $table->foreign('ciclo_id')->on('ciclos')->references('id');
            $table->foreign('ubigeo_id')->on('ubigeos')->references('id');
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
        Schema::dropIfExists('networks');
    }
}
