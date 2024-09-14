<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PostCommentTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 20; $i++) {
            DB::table('post_comment')->insert([
                'post_id' => $faker->numberBetween(1, 20), // Предполагаем, что у вас есть 20 постов
                'user_id' => $faker->numberBetween(1, 20), // Предполагаем, что у вас есть 20 пользователей
                'comment_text' => $faker->paragraph,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}