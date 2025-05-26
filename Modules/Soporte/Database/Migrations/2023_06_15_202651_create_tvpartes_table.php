<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Nwidart\Modules\Facades\Module;

class CreateTvpartesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tvpartes', function (Blueprint $table) {
           $table->id();
            $table->dateTime('date');
            $table->unsignedDecimal('cantidad', 18, 2);
            $table->unsignedDecimal('pricebuy', 18, 3)->default(0);
            $table->unsignedDecimal('price', 18, 3);
            $table->unsignedDecimal('igv', 18, 3);
            $table->unsignedDecimal('subtotaligv', 18, 3);
            $table->unsignedDecimal('subtotal', 18, 3);
            $table->unsignedDecimal('total', 18, 3);
            $table->decimal('increment', 5, 2)->default(0);
            $table->char('status', 1)->default(0);
            $table->char('gratuito', 1)->default(0);
            $table->unsignedBigInteger('parte_id');
            $table->bigInteger('user_id');
            $table->integer('tvparteable_id');
            $table->string('tvparteable_type', 255);
            $table->foreign('parte_id')->on('partes')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tvpartes');
    }
}
