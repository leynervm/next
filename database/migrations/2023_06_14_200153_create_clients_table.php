<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('document', 11);
            $table->string('name', 255);
            $table->string('email', 255)->nullable();
            $table->date('nacimiento')->nullable();
            $table->string('sexo', 1)->nullable();
            $table->bigInteger('pricetype_id')->nullable();
            // $table->bigInteger('channelsale_id')->nullable();
            $table->bigInteger('user_id')->nullable();

            $table->foreign('pricetype_id')->on('pricetypes')->references('id');
            // $table->foreign('channelsale_id')->on('channelsales')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
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
        Schema::dropIfExists('clients');
    }
}
