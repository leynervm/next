<?php

use App\Models\Cuenta;
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
        Schema::create('cuentas', function (Blueprint $table) {
            $table->id();
            $table->string('account', 100);
            $table->string('descripcion', 100);
            $table->enum('default', [Cuenta::ACTIVO, Cuenta::INACTIVO])->default(Cuenta::INACTIVO);
            $table->enum('status', [Cuenta::ACTIVO, Cuenta::INACTIVO])->default(Cuenta::ACTIVO);
            $table->bigInteger('banco_id')->nullable();
            $table->foreign('banco_id')->on('bancos')->references('id');
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
        Schema::dropIfExists('cuentas');
    }
};
