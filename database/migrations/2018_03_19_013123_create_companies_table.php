<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->string('pic')->nullable();
            $table->string('abbreviation')->index();
            $table->string('industry_str');
            $table->string('industry_code')->index();
            $table->string('stock')->nullable();
            $table->string('people');
            $table->string('address_str')->nullable();
            $table->integer('address_code')->nullable()->index();
            $table->text('detial')->nullable();
            $table->enum('status', [-1, 0, 1])->default(0)->index();
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
        Schema::dropIfExists('companies');
    }
}
