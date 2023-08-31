<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Nwidart\Modules\Facades\Module;

class CreateOrderbuypartesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $moduleName = 'Soporte';

        if (Module::isEnabled($moduleName)) {
            Schema::create('orderbuypartes', function (Blueprint $table) {
                $table->id();
                $table->decimal('cantidad', 10, 2);
                $table->decimal('pricebuy', 10, 2);
                $table->decimal('priceusbuy', 10, 2);
                $table->decimal('igv', 10, 2);
                $table->decimal('subototal', 10, 2);
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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orderbuypartes');
    }
}
