<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Seeder;

class RelationalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Comment::query()->delete();
        Article::query()->delete();
        User::query()->delete();

        // テストユーザ作成
        User::create([
            'name' => '田中太郎',
            'email' => 'taro@example.com',
            'password' => 'password',
        ]);

        // ユーザとその記事を作成
        User::factory()->count(2)
        ->has(
            Article::factory()->count(2)
            ->state(function (array $attributes, User $user) {
                return[
                    'user_id' => $user->id,
                    'username' => $user->name,
                ];
            })
        )->create();

        // コメント用ユーザを作成
        $commentUsers = User::factory()->count(3)->create();

        $userIds = $commentUsers->pluck('id');
        $articleIds = Article::all()->pluck('id');

        // 記事のコメントを作成
        Comment::factory()->count(10)
        ->state(function () use ($articleIds, $userIds) {
            return[
                'article_id' => $articleIds->random(),
                'user_id' => $userIds->random(),
            ];
        })
        ->create();
    }
}
