<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPositionNameToExpectPositions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expect_positions', function (Blueprint $table) {
            $table->string('position_name', 20)->after('position_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expect_positions', function (Blueprint $table) {
            $table->dropColumn('position_name');
        });
    }
}
