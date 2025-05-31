<?php

use App\Enums\MovimientosEnum;
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
            $table->decimal('amount', 18, 4);
            $table->decimal('tipocambio', 7, 3)->nullable();
            $table->decimal('totalamount', 18, 4);
            $table->string('typemovement', 7);
            $table->string('referencia', 255)->nullable();
            $table->text('detalle')->nullable();
            $table->unsignedTinyInteger('moneda_id');
            $table->unsignedTinyInteger('methodpayment_id');
            $table->unsignedTinyInteger('concept_id');
            $table->unsignedBigInteger('openbox_id');
            $table->unsignedBigInteger('monthbox_id');
            $table->unsignedTinyInteger('sucursal_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('moneda_id')->on('monedas')->references('id');
            $table->foreign('methodpayment_id')->on('methodpayments')->references('id');
            $table->foreign('concept_id')->on('concepts')->references('id');
            $table->foreign('openbox_id')->on('openboxes')->references('id');
            $table->foreign('monthbox_id')->on('monthboxes')->references('id');
            $table->foreign('sucursal_id')->on('sucursals')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
            $table->integer('cajamovimientable_id');
            $table->string('cajamovimientable_type');
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
