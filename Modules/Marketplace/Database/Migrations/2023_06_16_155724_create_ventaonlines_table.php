<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentaonlinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventaonlines', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->integer('code');
            $table->decimal('exonerado', 10, 2);
            $table->decimal('gravado', 10, 2);
            $table->decimal('otros', 10, 2);
            $table->decimal('igv', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('descuentos', 10, 2);
            $table->decimal('total', 10, 2);
            $table->date('shipmentdate')->nullable();
            $table->integer('delete')->default(0);
            $table->bigInteger('moneda_id')->nullable();
            $table->bigInteger('typepayment_id')->nullable();
            $table->bigInteger('client_id')->nullable();
            $table->bigInteger('shipmenttype_id')->nullable();
            $table->bigInteger('trackingstate_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->foreign('moneda_id')->on('monedas')->references('id');
            $table->foreign('typepayment_id')->on('typepayments')->references('id');
            $table->foreign('client_id')->on('clients')->references('id');
            $table->foreign('shipmenttype_id')->on('shipmenttypes')->references('id');
            $table->foreign('trackingstate_id')->on('trackingstates')->references('id');
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
        Schema::dropIfExists('ventaonlines');
    }
}
