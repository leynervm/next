<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Nwidart\Modules\Facades\Module;

class CreateItemseriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itemseries', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->integer('status')->default(0);
            $table->integer('delete')->default(0);
            $table->bigInteger('serie_id')->nullable();
            $table->bigInteger('tvitem_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->foreign('serie_id')->on('series')->references('id');
            $table->foreign('tvitem_id')->on('tvitems')->references('id');
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
        Schema::dropIfExists('itemseries');
    }
}
