<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class CompanyTableSeeder extends Seeder
{
    public function run(Faker $faker)
    {
        factory(\App\Models\Company::class, 10)->create();
    }
}