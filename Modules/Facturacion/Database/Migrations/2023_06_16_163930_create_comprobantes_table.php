<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComprobantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comprobantes', function (Blueprint $table) {
            $table->id();
            $table->string('seriecompleta', 13);
            $table->dateTime('date');
            $table->dateTime('expire');
            $table->string('direccion', 300);
            $table->decimal('gravado', 12, 4);
            $table->decimal('exonerado', 12, 4);
            $table->decimal('inafecto', 12, 4)->default(0);
            $table->decimal('gratuito', 12, 4)->default(0);
            $table->decimal('descuento', 12, 4)->default(0);
            $table->decimal('otros', 12, 4)->default(0);
            $table->decimal('igv', 12, 4)->default(0);
            $table->decimal('igvgratuito', 12, 4)->default(0);
            $table->decimal('subtotal', 12, 4);
            $table->decimal('total', 12, 4);
            $table->decimal('paymentactual', 12, 4);
            $table->decimal('percent', 5, 2);
            $table->string('hash', 32)->nullable();
            $table->string('referencia', 50);
            $table->tinyInteger('codesunat')->nullable();
            $table->string('descripcion', 255)->nullable();
            $table->text('notasunat')->nullable();
            $table->string('leyenda', 255);
            $table->bigInteger('client_id')->nullable();
            $table->tinyInteger('typepayment_id');
            $table->tinyInteger('seriecomprobante_id');
            $table->tinyInteger('moneda_id');
            $table->tinyInteger('sucursal_id')->nullable();
            $table->bigInteger('user_id');
            $table->integer('facturable_id');
            $table->string('facturable_type', 100);
            $table->foreign('client_id')->on('clients')->references('id');
            $table->foreign('typepayment_id')->on('typepayments')->references('id');
            $table->foreign('seriecomprobante_id')->on('seriecomprobantes')->references('id');
            $table->foreign('moneda_id')->on('monedas')->references('id');
            $table->foreign('sucursal_id')->on('sucursals')->references('id');
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
        Schema::dropIfExists('comprobantes');
    }
}
