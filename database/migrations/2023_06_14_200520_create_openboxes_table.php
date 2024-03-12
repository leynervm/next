<?php

use App\Models\Openbox;
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
        Schema::create('openboxes', function (Blueprint $table) {
            $table->id();
            $table->dateTime('startdate');
            $table->dateTime('expiredate');
            $table->dateTime('closedate')->nullable();
            $table->decimal('apertura', 10, 2)->default(0);
            $table->decimal('totalcash', 10, 2)->default(0);
            $table->decimal('totaltransfer', 10, 2)->default(0);
            $table->enum('status', [Openbox::ACTIVO, Openbox::INACTIVO])->default(Openbox::ACTIVO);
            $table->unsignedSmallInteger('box_id');
            $table->unsignedTinyInteger('sucursal_id');
            $table->bigInteger('user_id')->nullable();
            $table->foreign('box_id')->on('boxes')->references('id');
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
        Schema::dropIfExists('openboxes');
    }
};
