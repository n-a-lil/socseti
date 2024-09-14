<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class NotificationsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 20; $i++) {
            DB::table('notifications')->insert([
                'user_id' => $faker->numberBetween(1, 20), // Предполагаем, что у вас есть 20 пользователей
                'notification_type' => $faker->randomElement(['like', 'comment', 'follow']),
                'content' => $faker->sentence,
                'read' => $faker->randomElement(['yes', 'no']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}