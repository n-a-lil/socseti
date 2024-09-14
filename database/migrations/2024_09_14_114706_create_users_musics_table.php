<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersMusicsTable extends Migration
{
    public function up()
    {
        Schema::create('users_musics', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('music_title');
            $table->string('music_artist');
            $table->string('music_url');
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('users_musics');
    }
}