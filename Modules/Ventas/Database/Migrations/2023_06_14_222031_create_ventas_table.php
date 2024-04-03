<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->string('seriecompleta', 13);
            $table->string('direccion', 255);
            $table->decimal('exonerado', 18, 4);
            $table->decimal('gravado', 18, 4);
            $table->decimal('inafecto', 18, 4)->default(0);
            $table->decimal('gratuito', 18, 4)->default(0);
            $table->decimal('descuento', 18, 4)->default(0);
            $table->decimal('otros', 18, 4)->default(0);
            $table->decimal('igv', 18, 4)->default(0);
            $table->decimal('igvgratuito', 18, 4)->default(0);
            $table->decimal('subtotal', 10, 4);
            $table->decimal('total', 18, 4);
            $table->decimal('tipocambio', 7, 4)->nullable();
            $table->decimal('increment', 4, 2)->default(0);
            $table->decimal('paymentactual', 18, 4);
            $table->bigInteger('moneda_id');
            $table->unsignedTinyInteger('typepayment_id');
            $table->bigInteger('seriecomprobante_id');
            $table->bigInteger('client_id')->nullable();
            $table->bigInteger('user_id');
            $table->unsignedTinyInteger('sucursal_id')->nullable();
            $table->foreign('moneda_id')->on('monedas')->references('id');
            $table->foreign('typepayment_id')->on('typepayments')->references('id');
            $table->foreign('seriecomprobante_id')->on('seriecomprobantes')->references('id');
            $table->foreign('client_id')->on('clients')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
            $table->foreign('sucursal_id')->on('sucursals')->references('id');
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
        Schema::dropIfExists('ventas');
    }
}
