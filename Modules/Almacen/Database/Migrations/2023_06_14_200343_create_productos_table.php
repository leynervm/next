<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->string('slug')->unique();
            $table->string('modelo', 255)->nullable();
            $table->decimal('pricebuy', 10, 2);
            $table->decimal('priceusbuy', 10, 2)->nullable()->default(0);
            $table->decimal('pricesale', 10, 2)->nullable()->default(0);
            $table->decimal('percent', 10, 2)->nullable()->default(18);
            $table->decimal('igv', 10, 2)->default(0);
            $table->dateTime('lastingreso')->nullable();
            $table->integer('publicado')->default(0);
            $table->string('sku', 24)->nullable();
            $table->integer('views')->default(0);
            $table->integer('delete')->default(0);
            $table->bigInteger('almacenarea_id')->nullable();
            $table->bigInteger('estante_id')->nullable();
            $table->bigInteger('marca_id')->nullable();
            // $table->bigInteger('modelo_id')->nullable();
            $table->bigInteger('category_id')->nullable();
            $table->bigInteger('subcategory_id')->nullable();
            $table->bigInteger('tribute_id')->nullable();
            $table->bigInteger('unit_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->foreign('almacenarea_id')->on('almacenareas')->references('id');
            $table->foreign('estante_id')->on('estantes')->references('id');
            $table->foreign('marca_id')->on('marcas')->references('id');
            // $table->foreign('modelo_id')->on('modelos')->references('id');
            $table->foreign('category_id')->on('categories')->references('id');
            $table->foreign('subcategory_id')->on('subcategories')->references('id');
            $table->foreign('tribute_id')->on('tributes')->references('id');
            $table->foreign('unit_id')->on('units')->references('id');
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
        Schema::dropIfExists('productos');
    }
}
