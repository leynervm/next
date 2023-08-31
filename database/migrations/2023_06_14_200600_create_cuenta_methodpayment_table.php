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
        Schema::create('cuenta_methodpayment', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cuenta_id')->nullable();
            $table->bigInteger('methodpayment_id')->nullable();
            
            $table->foreign('cuenta_id')->on('cuentas')->references('id');
            $table->foreign('methodpayment_id')->on('methodpayments')->references('id');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cuenta_methodpayment');
    }
};
