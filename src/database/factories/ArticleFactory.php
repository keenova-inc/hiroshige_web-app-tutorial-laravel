<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
    * Define the model's default state.
    *
    * @return array<string, mixed>
    */
    public function definition(): array
    {
        return [
            'title' => fake()->title(),
            'content' =>  fake()->text(),
            'username' => fake()->name(),
        ];
    }

    /**
     * ユーザと紐づけて記事を作成
     */
    public function withUser(int $articleCount)
    {
        User::factory()
        ->has(
            $this->count($articleCount)
            ->state(function (array $attributes, User $user) {
                return[
                    'user_id' => $user->id,
                    'username' => $user->name,
                ];
            })
        )->create();
    }
}
