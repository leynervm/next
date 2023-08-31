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
            $table->decimal('exonerado', 10, 4);
            $table->decimal('gravado', 10, 4);
            $table->decimal('descuento', 10, 4);
            $table->decimal('otros', 10, 4);
            $table->decimal('igv', 10, 4);
            $table->decimal('subtotal', 10, 4);
            $table->decimal('total', 10, 4);
            $table->decimal('percent', 10, 2);
            $table->string('hash', 32)->nullable();
            $table->string('referencia', 50);
            $table->integer('codesunat')->nullable();
            $table->string('descripcion', 255)->nullable();
            $table->string('notasunat', 255)->nullable();
            $table->string('leyenda', 255);
            $table->integer('delete')->default(0);
            $table->bigInteger('client_id')->nullable();
            $table->bigInteger('tribute_id')->nullable();
            $table->bigInteger('typepayment_id')->nullable();
            $table->bigInteger('typecomprobante_id')->nullable();
            // $table->bigInteger('seriecomprobante_id')->nullable();
            $table->bigInteger('moneda_id')->nullable();
            $table->bigInteger('empresa_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->integer('facturable_id');
            $table->string('facturable_type', 100);
            $table->foreign('client_id')->on('clients')->references('id');
            $table->foreign('tribute_id')->on('tributes')->references('id');
            $table->foreign('typepayment_id')->on('typepayments')->references('id');
            $table->foreign('typecomprobante_id')->on('typecomprobantes')->references('id');
            // $table->foreign('seriecomprobante_id')->on('seriecomprobantes')->references('id');
            $table->foreign('moneda_id')->on('monedas')->references('id');
            $table->foreign('empresa_id')->on('empresas')->references('id');
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
        Schema::dropIfExists('comprobantes');
    }
}
