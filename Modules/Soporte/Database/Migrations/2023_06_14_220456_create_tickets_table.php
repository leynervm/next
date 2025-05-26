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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->string('seriecompleta');
            $table->dateTime('dateasigned')->nullable();
            $table->string('detalle', 255);
            $table->decimal('total', 10, 2)->default(0);
            $table->string('qr', 100);
            $table->unsignedInteger('atencion_id');
            $table->unsignedInteger('condition_id');
            $table->unsignedInteger('centerservice_id')->nullable();
            $table->unsignedInteger('priority_id')->nullable();
            $table->unsignedInteger('area_id')->nullable();
            $table->unsignedInteger('entorno_id')->nullable();
            $table->unsignedInteger('estate_id')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->unsignedInteger('userasigned_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('atencion_id')->on('atencions')->references('id');
            $table->foreign('condition_id')->on('conditions')->references('id');
            $table->foreign('centerservice_id')->on('centerservices')->references('id');
            $table->foreign('priority_id')->on('priorities')->references('id');
            $table->foreign('area_id')->on('areas')->references('id');
            $table->foreign('entorno_id')->on('entornos')->references('id');
            $table->foreign('estate_id')->on('estates')->references('id');
            $table->foreign('client_id')->on('clients')->references('id');
            $table->foreign('userasigned_id')->on('users')->references('id');
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
        Schema::dropIfExists('tickets');
    }
};
