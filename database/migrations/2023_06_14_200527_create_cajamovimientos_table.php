<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCajamovimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cajamovimientos', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->decimal('amount', 10, 2);
            $table->string('typemovement', 1);
            $table->string('referencia', 255)->nullable();
            $table->string('detalle', 255)->nullable();
            $table->bigInteger('moneda_id')->nullable();
            $table->bigInteger('methodpayment_id')->nullable();
            $table->bigInteger('cuenta_id')->nullable();
            // $table->bigInteger('typemovement_id')->nullable();
            $table->bigInteger('concept_id')->nullable();
            $table->bigInteger('opencaja_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->foreign('moneda_id')->on('monedas')->references('id');
            $table->foreign('methodpayment_id')->on('methodpayments')->references('id');
             $table->foreign('cuenta_id')->on('cuentas')->references('id');
            // $table->foreign('typemovement_id')->on('typemovements')->references('id');
            $table->foreign('concept_id')->on('concepts')->references('id');
            $table->foreign('opencaja_id')->on('opencajas')->references('id');
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
        Schema::dropIfExists('cajamovimientos');
    }
}
