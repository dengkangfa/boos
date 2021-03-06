<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkExperiencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_experiences', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->string('company_name');
            $table->date('start_time');
            $table->date('end_time')->nullable();
            $table->integer('position_type')->unsigned();
            $table->string('position_name')->nullable();
            $table->string('industry')->nullable();
            $table->string('work_emphasis');
            $table->string('subordinate')->nullable();
            $table->text('responsibility');
            $table->text('performance')->nullable();
            $table->boolean('veiled')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_experiences');
    }
}
