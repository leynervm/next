<?php

use App\Models\Opencaja;
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
        Schema::create('opencajas', function (Blueprint $table) {
            $table->id();
            $table->dateTime('startdate');
            $table->dateTime('expiredate')->nullable();
            $table->decimal('startmount', 10, 2)->default(0);
            $table->decimal('totalcash', 10, 2)->default(0);
            $table->decimal('totaltransfer', 10, 2)->default(0);
            $table->enum('status', [Opencaja::ACTIVO, Opencaja::INACTIVO])->default(Opencaja::ACTIVO);
            $table->bigInteger('caja_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->foreign('caja_id')->on('cajas')->references('id');
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
        Schema::dropIfExists('opencajas');
    }
};
