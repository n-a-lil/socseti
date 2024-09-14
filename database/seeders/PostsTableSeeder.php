<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PostsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 20; $i++) {
            DB::table('posts')->insert([
                'user_id' => $faker->numberBetween(1, 20), // Предполагаем, что у вас есть 20 пользователей
                'text' => $faker->paragraph,
                'image' => $faker->imageUrl(640, 480, 'cats'), // Генерация случайного URL изображения
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}