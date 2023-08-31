<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOrderbuysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('requeriments')) {
            return;
        }

        Schema::table('requeriments', function (Blueprint $table) {
            $table->foreign('orderbuy_id')->on('orderbuys')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requeriments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('orderbuy_id');
            // $table->foreign('orderbuy_id')->on('orderbuys')->references('id');
        });
    }
}
