<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Nwidart\Modules\Facades\Module;

class CreateRequerimentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('requeriments', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->decimal('cantidad', 10, 2);
            $table->string('descripcion', 255);
            $table->string('modelo', 100)->nullable();
            $table->string('ordenproveedor', 24)->nullable();
            $table->integer('status')->default(0);
            $table->integer('delete')->default(0);
            $table->bigInteger('unit_id')->nullable();
            $table->bigInteger('marca_id')->nullable();
            $table->bigInteger('proveedor_id')->nullable();
            $table->bigInteger('employer_id')->nullable();
            $table->bigInteger('order_id')->nullable();
            $table->bigInteger('parte_id')->nullable();
            $table->bigInteger('orderbuy_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->foreign('unit_id')->on('units')->references('id');
            $table->foreign('marca_id')->on('marcas')->references('id');
            $table->foreign('proveedor_id')->on('proveedors')->references('id');
            // $table->foreign('employer_id')->on('employers')->references('id');
            $table->foreign('order_id')->on('orders')->references('id');
            $table->foreign('parte_id')->on('partes')->references('id');
            // $table->foreign('orderbuy_id')->on('orderbuys')->references('id');
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
        Schema::dropIfExists('requeriments');
    }
}
