<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Создание таблицы post_comment
        Schema::create('post_comments', function (Blueprint $table) {
            $table->id();
            $table->integer('post_id');
            $table->integer('user_id');
            $table->text('comment_text');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Удаление таблицы post_comment
        Schema::dropIfExists('post_comments');

    }
}
