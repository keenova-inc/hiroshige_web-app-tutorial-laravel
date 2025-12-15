<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Comment::truncate();
        Article::truncate();

        $origin = ['created_at' => now(), 'updated_at' => now()];
        $data = [
            [
                "title" => '記事1',
                'content' => '本文1',
                'username' => 'ユーザ',
            ],
            [
                'title' => '記事2',
                'content' => '本文2',
                'username' => 'ユーザ',
            ],
        ];

        foreach ($data as &$d) {
            $d = array_merge($d, $origin);
        }

        Article::insert($data);
    }
}
