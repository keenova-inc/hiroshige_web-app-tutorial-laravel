<?php

use App\Consts\CommonConst;
use App\Models\{Article, Comment, User};
use Illuminate\Support\Facades\Log;

it('記事一覧を取得', function () {
    // データを準備
    Article::factory()->count(10)->create();

    /** @var \Tests\TestCase $this */
    $response = $this->getJson('api/articles');

    $response
    ->assertOk()
    ->assertJsonCount(CommonConst::PER_PAGE, 'articles.data');
});

it('記事を取得', function () {
    $commentCount = 2;
    // データを準備
    $articles = Article::factory()
    ->has(Comment::factory()->count($commentCount))
    ->count(3)->create();
    // Log::debug(print_r($articles->toArray(), true));
    $articleId = $articles->first()->id;

    /** @var \Tests\TestCase $this */
    $response = $this->getJson('api/articles/' . $articleId);

    $response->assertOk()
    ->assertJson(['article' => [
        'id' => $articleId,
        'comments' => [
            0 => ['article_id' => $articleId]
        ]
    ]])
    ->assertJsonCount($commentCount, 'article.comments');
});

it('記事を作成', function () {
    // データを準備
    $user = User::factory()->create();

    $createArray = [
        'title' => '記事のタイトル',
        'content' => '記事の内容',
        'username' => '記事の著者名'
    ];

    /** @var \Tests\TestCase $this */
    $response = $this->actingAs($user)
        ->postJson('api/articles', $createArray);

    $response->assertCreated();
    $this->assertDatabaseHas('articles', $createArray);
});

it('記事を更新', function () {
    // データを準備
    $user = User::factory()->create();
    $article = Article::factory()->create();

    $articleId = $article->id;
    $updateArray = [
        'title' => '記事のタイトル（更新後）',
        'content' => '記事の内容（更新後）',
        'username' => '記事の著者名（更新後）'
    ];

    /** @var \Tests\TestCase $this */
    $response = $this->actingAs($user)
        ->putJson('api/articles/' . $articleId, $updateArray);

    $response->assertOk();
    $this->assertDatabaseHas('articles', $updateArray);
});

it('記事を削除', function () {
    // データを準備
    $user = User::factory()->create();
    $article = Article::factory()->create();

    $articleId = $article->id;
    /** @var \Tests\TestCase $this */
    $response = $this->actingAs($user)
        ->deleteJson('api/articles/' . $articleId);

    $response->assertOk();
    $this->assertDatabaseMissing('articles', $article->toArray());
});
