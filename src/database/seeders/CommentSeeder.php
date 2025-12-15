<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Comment::truncate();

        $article = Article::find(1);
        if (is_null($article)) {
            $article = Article::create([
                'title' => '記事1',
                'content' => '本文1',
                'username' => 'ユーザ',
            ]);
        }
        $article->comments()->createMany([
            [
                'message' => 'コメント1',
            ],
            [
                'message' => 'コメント2',
            ],
        ]);
    }
}
