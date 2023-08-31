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
            $table->decimal('cantidad', 10, 2);
            $table->decimal('pricebuy', 10, 2);
            $table->decimal('price', 10, 2);
            $table->decimal('igv', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('total', 10, 2);
            $table->integer('status')->default(0);
            $table->decimal('increment', 10, 2)->default(0);
            $table->integer('delete')->default(0);
            $table->bigInteger('parte_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->integer('tvparteable_id');
            $table->string('tvparteable_type', 255);
            $table->foreign('parte_id')->on('partes')->references('id');
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
        Schema::dropIfExists('tvpartes');
    }
}
