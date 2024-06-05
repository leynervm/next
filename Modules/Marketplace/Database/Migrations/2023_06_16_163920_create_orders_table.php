<?php

use App\Enums\StatusPayWebEnum;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->string('seriecompleta', 13);
            $table->decimal('exonerado', 18, 4);
            $table->decimal('gravado', 18, 4);
            $table->decimal('igv', 18, 4)->default(0);
            $table->decimal('subtotal', 10, 4);
            $table->decimal('total', 18, 4);
            $table->decimal('tipocambio', 7, 4)->nullable();
            $table->json('receiverinfo');
            $table->char('status', 1)->default(StatusPayWebEnum::PENDIENTE->value);
            $table->char('methodpay', 1)->nullable();
            $table->unsignedBigInteger('direccion_id')->nullable();
            $table->unsignedBigInteger('moneda_id');
            $table->unsignedBigInteger('client_id');
            $table->unsignedSmallInteger('shipmenttype_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('direccion_id')->on('direccions')->references('id');
            $table->foreign('moneda_id')->on('monedas')->references('id');
            $table->foreign('client_id')->on('clients')->references('id');
            $table->foreign('shipmenttype_id')->on('shipmenttypes')->references('id');
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
        Schema::dropIfExists('orders');
    }
}
