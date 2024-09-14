<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ImagesTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 20; $i++) {
            DB::table('images')->insert([
                'user_id' => $faker->numberBetween(1, 20), // Предполагаем, что у вас есть 20 пользователей
                'url' => $faker->imageUrl(640, 480, 'cats'), // Генерация случайного URL изображения
                'main' => $faker->numberBetween(0, 1), // 0 или 1 для поля 'main'
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}