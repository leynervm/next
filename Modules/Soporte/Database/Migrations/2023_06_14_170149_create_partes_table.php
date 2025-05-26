<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Soporte\Entities\Parte;

class CreatePartesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partes', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->decimal('cantidad', 12, 2);
            $table->string('descripcion', 255);
            $table->string('serie', 32)->nullable();
            $table->string('code', 6)->nullable();
            $table->decimal('pricebuy', 12, 3);
            $table->decimal('igv', 12, 3)->default(0);
            $table->decimal('pricesale', 12, 3);
            $table->enum('condicion', [Parte::NUEVO, Parte::USADO, Parte::AVERIADO])->default(0);
            $table->integer('status')->default(Parte::DISPONIBLE);
            $table->string('referencia', 100);
            $table->unsignedInteger('marca_id');
            $table->unsignedInteger('unit_id');
            $table->unsignedInteger('compra_id')->nullable();
            $table->unsignedInteger('user_id');
            $table->foreign('marca_id')->on('marcas')->references('id');
            $table->foreign('unit_id')->on('units')->references('id');
            $table->foreign('compra_id')->on('compras')->references('id');
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
        Schema::dropIfExists('partes');
    }
}
