<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDerivadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Schema::hasTable('derivados')) {
            return;
        }

        Schema::table('derivados', function (Blueprint $table) {
            $table->foreign('employer_id')->on('employers')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('derivados', function (Blueprint $table) {
            // Define aquí cómo deshacer los cambios realizados en el método up()
            $table->dropConstrainedForeignId('employer_id');
            // $table->foreign('employer_id')->on('employers')->references('id');
        });
    }
}
