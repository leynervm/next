<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('otheritems', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->text('name');
            $table->string('marca', 255)->nullable();
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
            $table->bigInteger('unit_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->integer('otheritemable_id');
            $table->string('otheritemable_type', 255);
            $table->foreign('unit_id')->on('units')->references('id');
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
        Schema::dropIfExists('otheritems');
    }
};
