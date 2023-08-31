<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturableitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturableitems', function (Blueprint $table) {
            $table->id();
            $table->text('descripcion');
            $table->string('code', 32);
            $table->decimal('cantidad', 10, 2);
            $table->decimal('price', 10, 2);
            $table->decimal('igv', 10, 2);           
            $table->decimal('subtotal', 10, 2);
            $table->decimal('total', 10, 2);
            $table->integer('delete')->default(0);
            $table->bigInteger('comprobante_id')->nullable();
            $table->foreign('comprobante_id')->on('comprobantes')->references('id');
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
        Schema::dropIfExists('facturableitems');
    }
}
