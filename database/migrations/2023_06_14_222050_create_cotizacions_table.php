<?php

use App\Models\Cotizacion;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCotizacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cotizacions', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->dateTime('date');
            $table->string('seriecompleta', 16);
            $table->text('direccion');
            $table->date('validez');
            $table->integer('entrega');
            $table->char('datecode', 1);
            $table->char('afectacionigv', 1);
            $table->decimal('tipocambio', 7, 4)->nullable();
            $table->decimal('igv', 12, 2)->nullable()->default(0);
            $table->decimal('subtotal', 12, 2)->nullable()->default(0);
            $table->decimal('total', 12, 2)->nullable()->default(0);
            $table->integer('status')->default(Cotizacion::DEFAULT);
            $table->text('detalle')->nullable();
            $table->bigInteger('moneda_id')->nullable();
            $table->bigInteger('typepayment_id')->nullable();
            $table->bigInteger('client_id')->nullable();
            $table->bigInteger('sucursal_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->foreign('moneda_id')->on('monedas')->references('id');
            $table->foreign('typepayment_id')->on('typepayments')->references('id');
            $table->foreign('client_id')->on('clients')->references('id');
            $table->foreign('sucursal_id')->on('sucursals')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
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
        Schema::dropIfExists('cotizacions');
    }
}
