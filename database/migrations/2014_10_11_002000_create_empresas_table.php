<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->tinyIncrements('id')->unsigned();
            $table->string('document', 11);
            $table->string('name', 255);
            $table->string('estado', 20);
            $table->string('condicion', 20);
            $table->string('direccion', 255);
            // $table->string('urbanizacion', 255)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('web', 255)->nullable();
            $table->string('icono', 100)->nullable();
            $table->string('cert', 100)->nullable();
            $table->string('passwordcert', 100)->nullable();
            $table->string('usuariosol', 32)->nullable();
            $table->string('clavesol', 32)->nullable();
            $table->string('clientid', 100)->nullable();
            $table->string('clientsecret', 100)->nullable();
            $table->decimal('montoadelanto', 10, 2)->nullable();
            $table->integer('uselistprice')->default(0);
            $table->integer('usepricedolar')->default(0);
            $table->integer('viewpricedolar')->default(0);
            $table->decimal('tipocambio', 10, 4)->nullable();
            $table->integer('tipocambioauto')->default(0);
            $table->integer('sendmode')->default(0);
            $table->decimal('igv', 5, 2);
            $table->integer('status')->default(0);
            $table->integer('default')->default(1);
            $table->bigInteger('ubigeo_id')->nullable();
            $table->foreign('ubigeo_id')->on('ubigeos')->references('id');
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
        Schema::dropIfExists('empresas');
    }
}
