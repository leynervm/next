<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuotacomprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuotacompras', function (Blueprint $table) {
            $table->id();
            $table->integer('cuota');
            $table->decimal('amount', 10, 2);
            $table->date('expiredate');
            $table->dateTime('datepayment')->nullable();
            $table->integer('delete')->default(0);
            $table->bigInteger('cajamovimiento_id')->nullable();
            $table->bigInteger('compra_id')->nullable();
            $table->bigInteger('userpayment_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->foreign('cajamovimiento_id')->on('cajamovimientos')->references('id');
            $table->foreign('compra_id')->on('compras')->references('id');
            $table->foreign('userpayment_id')->on('users')->references('id');
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
        Schema::dropIfExists('cuotacompras');
    }
}
