<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            FriendsTableSeeder::class,
            MessagesTableSeeder::class,
            PostsTableSeeder::class,
            NotificationsTableSeeder::class,
            MusicTableSeeder::class,
            ImagesTableSeeder::class,
            PostCommentTableSeeder::class,
            PostLikeTableSeeder::class,
            // Другие сидеры
        ]);
    }
}
