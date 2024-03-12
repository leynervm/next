<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('referencia', 32);
            $table->string('guia', 32);
            $table->string('detalle', 255)->nullable();
            $table->decimal('tipocambio', 7, 4)->nullable();
            $table->decimal('gravado', 12, 4)->default(0);
            $table->decimal('exonerado', 12, 4)->default(0);
            $table->decimal('igv', 12, 4)->default(0);
            $table->decimal('descuento', 10, 4)->default(0);
            $table->decimal('otros', 12, 4)->default(0);
            $table->decimal('total', 12, 4)->default(0);
            $table->char('status', 1)->default(0);
            $table->tinyInteger('moneda_id')->nullable();
            $table->tinyInteger('typepayment_id')->nullable();
            $table->bigInteger('proveedor_id')->nullable();
            $table->tinyInteger('sucursal_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->foreign('moneda_id')->on('monedas')->references('id');
            $table->foreign('typepayment_id')->on('typepayments')->references('id');
            $table->foreign('proveedor_id')->on('proveedors')->references('id');
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
        Schema::dropIfExists('compras');
    }
}
