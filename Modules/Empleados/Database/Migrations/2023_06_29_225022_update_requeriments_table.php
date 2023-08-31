<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRequerimentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('requeriments')) {
            return;
        }

        Schema::table('requeriments', function (Blueprint $table) {
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
        Schema::table('requeriments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('employer_id');
            // $table->foreign('employer_id')->on('employers')->references('id');
        });
    }
}
