<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('company_id');
            $table->string('type_str')->index();
            $table->integer('type_code')->index();
            $table->string('name');
            $table->string('place');
            $table->enum('nature', ['全职', '兼职', '实习']);
            $table->string('work_emphasis');
            $table->integer('low_salary');
            $table->integer('high_salary');
            $table->enum('seniority', ['不限', '应届生', '一年以内', '1-3年', '3-5年', '5-10年', '10年以上']);
            $table->enum('education', ['不限', '中专及以下', '高中', '大专', '本科', '硕士', '博士']);
            $table->text('description');
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
        Schema::dropIfExists('jobs');
    }
}
