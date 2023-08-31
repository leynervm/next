<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            // $table->integer('code');
            $table->dateTime('dateasigned')->nullable();
            $table->string('detalle', 255);
            $table->decimal('total', 10, 2)->nullable()->default(0);
            $table->string('qr', 100);
            $table->integer('delete')->default(0);
            $table->bigInteger('atencion_id')->nullable();
            // $table->bigInteger('atenciontype_id')->nullable();
            $table->bigInteger('condition_id')->nullable();
            $table->bigInteger('priority_id')->nullable();
            $table->bigInteger('area_id')->nullable();
            $table->bigInteger('entorno_id')->nullable();
            $table->bigInteger('estate_id')->nullable();
            $table->bigInteger('client_id')->nullable();
            $table->bigInteger('moneda_id')->nullable();
            $table->bigInteger('cajamovimiento_id')->nullable();
            $table->bigInteger('userasigned_id')->nullable();
            $table->bigInteger('user_id')->nullable();

            // $table->foreign('atenciontype_id')->on('atenciontypes')->references('id');
            $table->foreign('atencion_id')->on('atencions')->references('id');
            $table->foreign('condition_id')->on('conditions')->references('id');
            $table->foreign('priority_id')->on('priorities')->references('id');
            $table->foreign('area_id')->on('areas')->references('id');
            $table->foreign('entorno_id')->on('entornos')->references('id');
            $table->foreign('estate_id')->on('estates')->references('id');
            $table->foreign('client_id')->on('clients')->references('id');
            $table->foreign('moneda_id')->on('monedas')->references('id');
            $table->foreign('cajamovimiento_id')->on('cajamovimientos')->references('id');
            $table->foreign('userasigned_id')->on('users')->references('id');
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
