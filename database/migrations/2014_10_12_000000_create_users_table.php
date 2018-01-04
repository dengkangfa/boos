<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index()->nullable();
            $table->string('mobile')->unique()->index();
            $table->string('avatar')->nullable();
            $table->string('email')->unique()->nullable()->index();
            $table->string('password');
            $table->enum('gender', ['male', 'female']);
            $table->text('advantage')->nullable();
            $table->timestamp('job_date')->nullable();
            $table->timestamp('birth_date')->nullable();
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
        Schema::dropIfExists('users');
    }
}
