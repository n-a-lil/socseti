<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class MessagesTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 20; $i++) {
            DB::table('messages')->insert([
                'sender_id' => $faker->numberBetween(1, 20), // Предполагаем, что у вас есть 20 пользователей
                'receiver_id' => $faker->numberBetween(1, 20),
                'text' => $faker->sentence,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}