<?php

use App\Models\Slider;
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
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('url', 255);
            $table->string('urlmobile', 255);
            $table->text('link')->nullable();
            $table->unsignedSmallInteger('orden')->default(0);
            $table->date('start');
            $table->date('end')->nullable();
            $table->string('status', 1)->default(Slider::ACTIVO);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sliders');
    }
};
