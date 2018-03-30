<?php

use Carbon\Carbon;
use App\User;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
//        $identicon = new \Identicon\Identicon();
//        $users = [
//                'name' => 'admin',
//                'email' => 'admin@admin.com',
//                'mobile' => $faker->PhoneNumber,
//                'password' => Hash::make('admin'),
//                'created_at'  => Carbon::now(),
//                'updated_at'  => Carbon::now()
//        ];
//
//        DB::table('users')->insert($users);

        factory(\App\Models\User::class, 10)->create();
    }
}
