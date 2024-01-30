<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Nwidart\Modules\Facades\Module;

class CreateAdelantosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // $moduleName = 'Empleados';

        // if (Module::isEnabled($moduleName)) {
        Schema::create('adelantos', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->decimal('amount', 8, 2);
            $table->string('datemonth', 7);
            $table->integer('delete')->default(0);
            $table->bigInteger('employer_id')->nullable();
            $table->bigInteger('cajamovimiento_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->foreign('employer_id')->on('employers')->references('id');
            $table->foreign('cajamovimiento_id')->on('cajamovimientos')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
            $table->timestamps();
        });
        // }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adelantos');
    }
}
