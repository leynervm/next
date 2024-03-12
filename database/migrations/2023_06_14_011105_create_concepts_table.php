<?php

use App\Enums\DefaultConceptsEnum;
use App\Enums\MovimientosEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConceptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('concepts', function (Blueprint $table) {
            $table->tinyIncrements('id')->unsigned();
            $table->string('name', 100);
            $table->string('typemovement', 7);
            $table->string('default', 2)->default(DefaultConceptsEnum::DEFAULT->value);
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
        Schema::dropIfExists('concepts');
    }
}
