<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAddressToJobs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->string('doorplate')->nullable()->after('name');
            $table->string('street')->after('name');
            $table->string('area')->after('name');
            $table->string('city')->after('name');
            $table->string('province')->after('name');

            $table->dropColumn('place');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn('province');
            $table->dropColumn('city');
            $table->dropColumn('area');
            $table->dropColumn('street');
            $table->dropColumn('doorplate')->nullable();

            $table->string('place');
        });
    }
}
