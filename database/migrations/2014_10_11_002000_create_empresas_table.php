<?php

use App\Models\Empresa;
use App\Models\Sucursal;
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
            $table->string('whatsapp', 255)->nullable();
            $table->string('facebook', 255)->nullable();
            $table->string('youtube', 255)->nullable();
            $table->string('instagram', 255)->nullable();
            $table->string('tiktok', 255)->nullable();
            $table->string('icono', 100)->nullable();
            $table->string('logofooter', 100)->nullable();
            $table->string('logoimpresion', 100)->nullable();
            $table->string('cert', 100)->nullable();
            $table->string('passwordcert', 100)->nullable();
            $table->string('usuariosol', 32)->nullable();
            $table->string('clavesol', 32)->nullable();
            $table->string('clientid', 100)->nullable();
            $table->string('clientsecret', 100)->nullable();
            $table->decimal('montoadelanto', 10, 2)->nullable();
            $table->unsignedTinyInteger('uselistprice')->default(0);
            $table->unsignedTinyInteger('viewpriceantes')->default(0);
            $table->unsignedTinyInteger('viewlogomarca')->default(0);
            $table->unsignedTinyInteger('viewalmacens')->default(0);
            $table->unsignedTinyInteger('viewalmacensdetalle')->default(0);
            $table->unsignedTinyInteger('viewtextopromocion')->default(0);
            $table->string('textnovedad', 50)->nullable();
            $table->unsignedTinyInteger('usemarkagua')->default(0);
            $table->string('markagua', 100)->nullable();
            $table->string('alignmark', 25)->nullable()->default('center');
            $table->unsignedTinyInteger('widthmark')->nullable()->default(100);
            $table->unsignedTinyInteger('heightmark')->nullable()->default(100);
            $table->unsignedTinyInteger('usepricedolar')->default(0);
            $table->unsignedTinyInteger('viewpricedolar')->default(0);
            $table->unsignedTinyInteger('viewespecificaciones')->default(0);
            $table->unsignedTinyInteger('generatesku')->default(0);
            $table->decimal('tipocambio', 10, 4)->nullable();
            $table->unsignedTinyInteger('tipocambioauto')->default(0);
            $table->unsignedTinyInteger('sendmode')->default(0);
            $table->unsignedTinyInteger('afectacionigv')->default(0);
            $table->decimal('igv', 5, 2);
            $table->unsignedTinyInteger('limitsucursals')->nullable()->default(Sucursal::LIMITE);
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedTinyInteger('default')->default(1);
            $table->unsignedBigInteger('ubigeo_id')->nullable();
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
