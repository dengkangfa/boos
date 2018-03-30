<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/
$faker = \Faker\Factory::create('zh_CN');

$factory->define(App\Models\User::class, function ($faker) {
    static $password;
    $date_time = $faker->date.' '.$faker->time;
    $imageId = rand(1, 16);

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'avatar' => "images/avatar_{$imageId}.png",
        'mobile' => $faker->phoneNumber,
        'password' => $password ?: $password = bcrypt('secret'),
        'created_at' => $date_time,
        'updated_at' => $date_time,
    ];
});
