<?php

use App\Models\Empresa;
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
        Schema::create('guias', function (Blueprint $table) {
            $table->id();
            $table->string('seriecompleta', 13);
            $table->dateTime('date');
            $table->dateTime('expire');
            $table->dateTime('datetraslado');
            $table->string('ructransport', 11)->nullable();
            $table->string('nametransport', 255)->nullable();
            $table->string('rucproveedor', 11)->nullable();
            $table->string('nameproveedor', 255)->nullable();
            $table->string('placavehiculo', 8)->nullable();
            $table->string('documentdestinatario', 11);
            $table->string('namedestinatario', 255);
            $table->decimal('peso', 12, 4);
            $table->string('unit', 5);
            $table->tinyInteger('packages')->nullable();
            $table->string('direccionorigen', 255)->nullable();
            $table->string('anexoorigen', 4)->nullable();
            $table->string('direcciondestino', 255)->nullable();
            $table->string('anexodestino', 4)->nullable();
            $table->string('note', 255)->nullable();
            $table->string('indicadorvehiculosml', 1)->default(0);
            $table->string('indicadorconductor', 1)->default(0);
            $table->string('indicadordamds', 1)->default(0);
            $table->string('indicadortransbordo', 1)->default(0);
            $table->string('indicadorvehretorvacio', 1)->default(0);
            $table->string('indicadorvehretorenvacios', 1)->default(0);
            $table->string('hash', 32)->nullable();
            $table->string('codesunat', 4)->nullable();
            $table->text('descripcion')->nullable();
            $table->text('notasunat')->nullable();
            $table->char('referencia', 13)->nullable();
            $table->string('sendmode', 1)->default(Empresa::PRUEBA);
            $table->tinyInteger('motivotraslado_id');
            $table->tinyInteger('modalidadtransporte_id');
            $table->bigInteger('ubigeoorigen_id');
            $table->bigInteger('ubigeodestino_id');
            $table->bigInteger('client_id');
            $table->tinyInteger('seriecomprobante_id');
            $table->tinyInteger('sucursal_id');
            $table->bigInteger('user_id');
            $table->bigInteger('guiable_id')->nullable();
            $table->string('guiable_type')->nullable();
            $table->foreign('motivotraslado_id')->on('motivotraslados')->references('id');
            $table->foreign('modalidadtransporte_id')->on('modalidadtransportes')->references('id');
            $table->foreign('ubigeoorigen_id')->on('ubigeos')->references('id');
            $table->foreign('ubigeodestino_id')->on('ubigeos')->references('id');
            $table->foreign('client_id')->on('clients')->references('id');
            $table->foreign('seriecomprobante_id')->on('seriecomprobantes')->references('id');
            $table->foreign('sucursal_id')->on('sucursals')->references('id');
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
        Schema::dropIfExists('guias');
    }
};
