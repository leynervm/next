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
        Schema::create('claimbooks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->dateTime('date');
            $table->char('serie', 4);
            $table->unsignedBigInteger('correlativo');
            $table->string('document', 11);
            $table->string('name', 255);
            $table->string('direccion', 255);
            $table->char('telefono', 9);
            $table->string('email', 50);
            $table->string('channelsale', 50);
            $table->char('is_menor_edad', 1)->default(0);
            $table->string('document_apoderado', 8)->nullable();
            $table->string('name_apoderado', 255)->nullable();
            $table->string('direccion_apoderado', 255)->nullable();
            $table->char('telefono_apoderado', 9)->nullable();
            $table->string('biencontratado', 255);
            $table->text('descripcion_producto_servicio');
            $table->string('tipo_reclamo', 255);
            $table->text('detalle_reclamo');
            $table->string('pedido', 255)->nullable();
            $table->unsignedBigInteger('sucursal_id')->nullable();
            $table->foreign('sucursal_id')->references('id')->on('sucursals');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('claimbooks');
    }
};
