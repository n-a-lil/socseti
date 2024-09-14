<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class MusicTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 20; $i++) {
            DB::table('music')->insert([
                'user_id' => $faker->numberBetween(1, 20), // Предполагаем, что у вас есть 20 пользователей
                'music_title' => $faker->sentence(3),
                'misuc_artist' => $faker->name,
                'music_url' => $faker->url,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}