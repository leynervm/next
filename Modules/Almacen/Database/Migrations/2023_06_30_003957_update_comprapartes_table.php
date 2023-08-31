<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateComprapartesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('comprapartes')) {
            return;
        }

        Schema::table('comprapartes', function (Blueprint $table) {
            $table->foreign('compra_id')->on('compras')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comprapartes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('compra_id');
            // $table->foreign('compra_id')->on('compras')->references('id');
        });
    }
}
