<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 20; $i++) {
            DB::table('users')->insert([
                'name' => $faker->name,
                'surname' => $faker->lastname,
                'age' => $faker->numberBetween(18, 65),
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'), // Пароль по умолчанию
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}