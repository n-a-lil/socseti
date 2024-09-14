<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class FriendsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 20; $i++) {
            DB::table('friends')->insert([
                'user_id' => $faker->numberBetween(1, 20), // Предполагаем, что у вас есть 20 пользователей
                'friend_id' => $faker->numberBetween(1, 20),
                'status' => $faker->randomElement(['pending', 'accepted', 'rejected']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}