<?php

namespace Database\Factories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Article;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'message' => fake()->text(),
        ];
    }

    /**
     * ユーザと記事と紐づけコメントを作成
     */
    public function withUserAndArticle(
        int $commentCount,
        int $commentUserCount,
    ) {
        // コメント用ユーザを作成
        $commentUsers = User::factory()->count($commentUserCount)->create();
        $commentUserIds = $commentUsers->pluck('id');

        // 記事作成用ユーザを作成
        User::factory()
        // 記事を作成
        ->has(
            Article::factory()
            ->state(function (array $attributes, User $user) {
                return[
                    'user_id' => $user->id,
                    'username' => $user->name,
                ];
                // 記事のコメントを作成
            })->has(
                Comment::factory()->count($commentCount)
                ->state(function (array $attributes, Article $article) use ($commentUserIds) {
                    return [
                        'user_id' => $commentUserIds->random(),
                        'article_id' => $article->id,
                    ];
                })
            )
        )->create();
    }
}
