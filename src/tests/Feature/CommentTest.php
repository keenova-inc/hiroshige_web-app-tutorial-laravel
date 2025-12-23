<?php

use App\Consts\CommonConst;
use App\Models\{Article, Comment, User};
use Illuminate\Support\Facades\Log;

// beforeAll(function () {
//     // Prepare something once before any of this file's tests run...
// });

beforeEach(function () {
    // データを準備
    Article::factory()->has(Comment::factory()->count(10))
    ->count(3)->create();
});

it('記事のコメント一覧を取得', function () {
    /** @var \Tests\TestCase $this */
    $response = $this->getJson('api/articles/1/comments');
    // Log::debug(print_r($response->json(), true));

    $response->assertOk()
    ->assertJsonCount(CommonConst::PER_PAGE, 'comments.data');
});

it('記事のコメントを取得', function () {
    $comment = Comment::first();
    $commentId = $comment->id;
    $articleId = $comment->article_id;
    Log::debug("commentId: $commentId\n articleId:$articleId");

    /** @var \Tests\TestCase $this */
    $response = $this->getJson("api/articles/{$articleId}/comments/{$commentId}");
    $response->assertOk()
    ->assertJson(['comment' => ['id' => $commentId, 'article' => ['id' => $articleId]]]);
});

it('記事のコメントを作成', function () {
    // データを準備
    $user = User::factory()->create();
    $articleId = 2;

    $createArray = ['message' => '記事へのコメント'];

    /** @var \Tests\TestCase $this */
    $response = $this->actingAs($user)
        ->postJson("api/articles/{$articleId}/comments", $createArray);

    $response->assertCreated();
    $mergedArray = array_merge($createArray, ['article_id' => $articleId]);
    $this->assertDatabaseHas('comments', $mergedArray);
});

it('記事のコメントを更新', function () {
    // データを準備
    $user = User::factory()->create();

    $comment = Comment::first();
    $updateArray = ['message' => '記事へのコメント（更新後）'];

    /** @var \Tests\TestCase $this */
    $response = $this->actingAs($user)
    ->putJson("api/articles/{$comment->article_id}/comments/{$comment->id}",
    $updateArray);

    // Log::debug(print_r($response->json(), true));
    $mergedArray = array_merge($updateArray, [
        'id' => $comment->id,
        'article_id' => $comment->article_id
    ]);

    $response->assertOk();
    $this->assertDatabaseHas('comments', $mergedArray);
});

it('記事のコメントを削除', function () {
    // データを準備
    $user = User::factory()->create();

    $comment = Comment::first();
    /** @var \Tests\TestCase $this */
    $response = $this->actingAs($user)
    ->deleteJson("api/articles/{$comment->article_id}/comments/{$comment->id}");

    $response->assertOk();
    $this->assertDatabaseMissing('comments', $comment->toArray());

});


