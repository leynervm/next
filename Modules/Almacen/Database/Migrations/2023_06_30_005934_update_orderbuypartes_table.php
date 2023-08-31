<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOrderbuypartesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('orderbuypartes')) {
            return;
        }

        Schema::table('orderbuypartes', function (Blueprint $table) {
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
        Schema::table('orderbuypartes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('compra_id');
            // $table->foreign('compra_id')->on('compras')->references('id');
        });
    }
}
