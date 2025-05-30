<?php

use App\Models\Producto;
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
            $table->text('slug')->unique();
            $table->string('modelo', 255)->nullable();
            $table->decimal('pricebuy', 12, 3);
            $table->decimal('pricesale', 12, 3)->nullable()->default(0);
            $table->decimal('igv', 12, 3)->default(0);
            $table->string('code', 24)->nullable();
            $table->string('sku', 32)->nullable();
            $table->string('partnumber', 24)->nullable();
            $table->unsignedBigInteger('views')->default(0);
            $table->unsignedDecimal('minstock', 12, 2)->default(0);
            $table->char('requireserie', 1)->default(0);
            $table->char('publicado', 1)->default(0);
            $table->char('viewespecificaciones', 1)->default(0);
            $table->char('viewdetalle', 1)->default(Producto::VER_DETALLES);
            $table->char('visivility', 1)->default(0);
            $table->unsignedInteger('maxstockweb')->nullable();
            $table->decimal('precio_1', 12, 4)->nullable();
            $table->decimal('precio_2', 12, 4)->nullable();
            $table->decimal('precio_3', 12, 4)->nullable();
            $table->decimal('precio_4', 12, 4)->nullable();
            $table->decimal('precio_5', 12, 4)->nullable();
            $table->text('comentario')->nullable();
            $table->unsignedSmallInteger('almacenarea_id')->nullable();
            $table->unsignedSmallInteger('estante_id')->nullable();
            $table->bigInteger('marca_id')->nullable();
            $table->unsignedInteger('category_id')->nullable();
            $table->unsignedInteger('subcategory_id')->nullable();
            $table->unsignedInteger('unit_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('almacenarea_id')->on('almacenareas')->references('id');
            $table->foreign('estante_id')->on('estantes')->references('id');
            $table->foreign('marca_id')->on('marcas')->references('id');
            $table->foreign('category_id')->on('categories')->references('id');
            $table->foreign('subcategory_id')->on('subcategories')->references('id')->nullOnDelete();
            $table->foreign('unit_id')->on('units')->references('id');
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
