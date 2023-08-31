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
            $table->dateTime('date', 255);
            $table->string('referencia', 50);
            $table->string('descripcion', 255)->nullable();
            $table->decimal('pricedolar', 10, 2);
            $table->decimal('totalpayus', 10, 2);
            $table->decimal('totalpay', 10, 2);
            $table->decimal('otros', 10, 2);
            $table->integer('statuspay')->default(0);
            $table->integer('delete')->default(0);
            $table->bigInteger('moneda_id')->nullable();
            $table->bigInteger('typepayment_id')->nullable();
            $table->bigInteger('typemovement_id')->nullable();
            $table->bigInteger('proveedor_id')->nullable();
            $table->bigInteger('cajamovimiento_id')->nullable();
            $table->bigInteger('user_id')->nullable();

            $table->foreign('moneda_id')->on('monedas')->references('id');
            $table->foreign('typepayment_id')->on('typepayments')->references('id');
            $table->foreign('typemovement_id')->on('typemovements')->references('id');
            $table->foreign('proveedor_id')->on('proveedors')->references('id');
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
        Schema::dropIfExists('compras');
    }
}
