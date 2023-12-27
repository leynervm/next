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
            $table->integer('item');
            $table->text('descripcion');
            $table->decimal('cantidad', 10, 2);
            $table->decimal('price', 12, 4);
            $table->decimal('percent', 5, 2);
            $table->decimal('igv', 12, 4);   
            $table->decimal('subtotaligv', 12, 4);        
            $table->decimal('subtotal', 12, 4);
            $table->decimal('total', 12, 4);
            $table->string('code', 24);
            $table->string('unit', 5);
            $table->string('codetypeprice', 2);
            $table->string('afectacion', 2);
            $table->string('codeafectacion', 4);
            $table->string('nameafectacion', 3);
            $table->string('typeafectacion', 3);
            $table->string('abreviatureafectacion', 1);
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
