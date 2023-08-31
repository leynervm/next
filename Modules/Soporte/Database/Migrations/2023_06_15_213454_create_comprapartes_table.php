<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Nwidart\Modules\Facades\Module;

class CreateComprapartesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comprapartes', function (Blueprint $table) {
            $table->id();
            $table->decimal('cantidad', 10, 2);
            $table->decimal('pricebuy', 10, 2);
            $table->decimal('priceusbuy', 10, 2)->nullable();
            $table->decimal('igv', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->integer('delete')->default(0);
            $table->bigInteger('parte_id')->nullable();
            $table->bigInteger('compra_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->foreign('parte_id')->on('partes')->references('id');
            // $table->foreign('compra_id')->on('compras')->references('id');
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
        Schema::dropIfExists('comprapartes');
    }
}
