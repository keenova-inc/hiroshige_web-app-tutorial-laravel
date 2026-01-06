<?php declare(strict_types=1);

use App\Consts\CommonConst;
use App\Models\{Article, Comment, User};
use Mockery\MockInterface;
use App\Repositories\Comment\CommentRepositoryInterface;


describe('記事のコメント一覧を取得', function () {
    it('正常完了', function () {
        Comment::factory()->withUserAndArticle(10, 2);

        $articleId = Article::first()->id;
        /** @var \Tests\TestCase $this */
        $this->getJson("api/articles/$articleId/comments")
        ->assertOk()
        ->assertJsonCount(CommonConst::PER_PAGE, 'comments.data');
    });

    it('コメントが0件でも正常完了', function () {
        Article::factory()->withUser(1);

        $articleId = Article::first()->id;
        /** @var \Tests\TestCase $this */
        $this->getJson("api/articles/$articleId/comments")
        ->assertOk()
        ->assertJson(['comments' => [
            'data' => [],
            'total'=> 0
        ]]);
    });

    it('コメントが作成日時の降順、作成日時が同じ場合にはid順で並んでいる', function () {
        Comment::factory()->withUserAndArticle(10, 2);

        $firstId = 3;
        $secondId = 10;
        // 指定のidの作成日時のみ未来にする
        Comment::whereIn('id', [$firstId, $secondId])
        ->update(['created_at' => now()->addMinute()]);

        $articleId = Article::first()->id;
        /** @var \Tests\TestCase $this */
        $response = $this->getJson("api/articles/$articleId/comments");
        $response
        ->assertOk()
        ->assertJsonCount(CommonConst::PER_PAGE, 'comments.data')
        ->assertJsonPath('comments.data.0.id', $firstId)
        ->assertJsonPath('comments.data.1.id', $secondId);
    });

    it('不正なページが指定されたらステータス400で返す', function () {
        Article::factory()->withUser(1);
        $articleId = Article::first()->id;

        /** @var \Tests\TestCase $this */
        $this->getJson("api/articles/$articleId/comments?page=abc")
        ->assertStatus(400);
    });

    it('存在しない記事IDが指定されたらステータス404で返す', function() {
        /** @var \Tests\TestCase $this */
        $this->getJson("api/articles/99999/comments?page=abc")
        ->assertStatus(404);
    });

    it('例外が発生した場合ステータス500で返す', function () {
        Comment::factory()->withUserAndArticle(10, 2);

        /** @var \Tests\TestCase $this */
        $this->mock(CommentRepositoryInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('search')
            ->andThrow(new Exception);
        });

        $articleId = Article::first()->id;
        /** @var \Tests\TestCase $this */
        $this->getJson("api/articles/$articleId/comments?page=1")
        ->assertStatus(500)
        ->assertJson(['message'=> trans('api.cant_get')]);
    });
});

describe('記事のコメントを取得', function () {
    beforeEach(function() {
        Comment::factory()->withUserAndArticle(2, 1);
    });

    it('正常完了', function () {
        $comment = Comment::first();
        $commentId = $comment->id;
        $articleId = $comment->article_id;
        // Log::debug("commentId: $commentId\n articleId:$articleId");

        /** @var \Tests\TestCase $this */
        $response = $this->getJson("api/articles/{$articleId}/comments/{$commentId}");
        $response->assertOk()
        ->assertJson(['comment' => ['id' => $commentId, 'article' => ['id' => $articleId]]]);
    });

    it('存在しないコメントIDが指定されたらステータス404で返す', function() {
        /** @var \Tests\TestCase $this */
        $this->getJson('api/articles/99999/comments/99999')
        ->assertStatus(404);
    });

    it('例外が発生した場合ステータス500で返す', function () {
        $comment = Comment::first();
        $commentId = $comment->id;
        $articleId = $comment->article_id;

        /** @var \Tests\TestCase $this */
        $this->mock(CommentRepositoryInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('find')
            ->andThrow(new Exception);
        });

        $this->getJson("api/articles/$articleId/comments/$commentId")
        ->assertStatus(500)
        ->assertJson(['message'=> trans('api.not_exist',
            ['id' => $articleId, 'attribute' => __('validation.attributes.message')]
        )]);
    });
});

describe('記事のコメントを作成', function () {
    beforeEach(function() {
        Article::factory()->withUser(1);
    });

    $createArray = ['message' => '記事へのコメント'];

    it('正常完了', function () use ($createArray) {
        $articleId = Article::first()->id;
        // データを準備
        $user = User::first();

        /** @var \Tests\TestCase $this */
        $response = $this->actingAs($user)
            ->postJson("api/articles/{$articleId}/comments", $createArray);

        $response->assertCreated();
        $mergedArray = array_merge($createArray, ['article_id' => $articleId]);
        $this->assertDatabaseHas('comments', $mergedArray);
    });

    it('未ログインの場合ステータス401で返す', function () use($createArray) {
        $articleId = Article::first()->id;
        /** @var \Tests\TestCase $this */
        $this->postJson("api/articles/{$articleId}/comments", $createArray)
        ->assertStatus(401);
    });

    it('コメントが空の場合422で返す', function () {
        $articleId = Article::first()->id;
        $user = User::first();
         /** @var \Tests\TestCase $this */
       $this->actingAs($user)
        ->postJson("api/articles/{$articleId}/comments", [])
        ->assertStatus(422);
    });

    it('例外が発生した場合ステータス500で返す', function () use ($createArray) {
        $articleId = Article::first()->id;

        /** @var \Tests\TestCase $this */
        $this->mock(CommentRepositoryInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('create')
            ->andThrow(new Exception);
        });

       $this->actingAs(User::first())
        ->postJson("api/articles/{$articleId}/comments", $createArray)
        ->assertStatus(500);
    });
});

describe('記事のコメントを更新', function() {
    beforeEach(function() {
        Comment::factory()->withUserAndArticle(2, 1);
    });

    $updateArray = ['message' => '記事へのコメント（更新後）'];

    it('正常完了', function () use($updateArray) {
        $comment = Comment::first();
        $user = User::find($comment->user_id);

        /** @var \Tests\TestCase $this */
        $response = $this->actingAs($user)
        ->putJson("api/articles/{$comment->article_id}/comments/{$comment->id}",
        $updateArray);

        // Log::debug(print_r($response->json(), true));
        $mergedArray = array_merge($updateArray, [
            'id' => $comment->id,
            'article_id' => $comment->article_id,
            'user_id' => $user->id,
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('comments', $mergedArray);
    });

    it('存在しないコメントIDが指定された場合ステータス404で返す', function()  use($updateArray){
        $comment = Comment::first();
        $user = User::find($comment->user_id);
        /** @var \Tests\TestCase $this */
        $this->actingAs($user)
        ->putJson("api/articles/99999/comments/99999", $updateArray)
        ->assertStatus(404);
    });

    it('未ログインの場合ステータス401で返す', function () use($updateArray) {
        $comment = Comment::first();
        $commentId = $comment->id;
        $articleId = $comment->article_id;
        /** @var \Tests\TestCase $this */
        $this->putJson("api/articles/$articleId/comments/$commentId", $updateArray)
        ->assertStatus(401);
    });

    it('コメント作成者以外のユーザが更新しようとした場合403を返す', function () use ($updateArray) {
        $comment = Comment::first();
        $commentId = $comment->id;
        $articleId = $comment->article_id;
        $user = User::factory()->create();

        /** @var \Tests\TestCase $this */
        $this->actingAs($user)
        ->putJson("api/articles/$articleId/comments/$commentId", $updateArray)
        ->assertStatus(403);
    });

    it('コメントが空の場合422で返す', function () {
        $comment = Comment::first();
        $commentId = $comment->id;
        $articleId = $comment->article_id;
        $user = User::find($comment->user_id);

         /** @var \Tests\TestCase $this */
       $this->actingAs($user)
        ->putJson("api/articles/$articleId/comments/$commentId", [])
        ->assertStatus(422);
    });

    it('例外が発生した場合ステータス500で返す', function () use ($updateArray) {
        $comment = Comment::first();
        $commentId = $comment->id;
        $articleId = $comment->article_id;
        $user = User::find($comment->user_id);

        /** @var \Tests\TestCase $this */
        $this->mock(CommentRepositoryInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('update')
            ->andThrow(new Exception);
        });

       $this->actingAs($user)
        ->putJson("api/articles/$articleId/comments/$commentId", $updateArray)
        ->assertStatus(500);
    });
});

describe('記事のコメントを削除', function(){
    beforeEach(function() {
        Comment::factory()->withUserAndArticle(2, 1);
    });

    it('正常完了', function () {
        $comment = Comment::first();
        $commentId = $comment->id;
        $articleId = $comment->article_id;
        $user = User::find($comment->user_id);

        /** @var \Tests\TestCase $this */
        $this->actingAs($user)
        ->deleteJson("api/articles/$articleId/comments/$commentId")
        ->assertOk();
        $this->assertSoftDeleted('comments', $comment->toArray());
    });

    it('未ログインの場合ステータス401で返す', function () {
        $comment = Comment::first();
        $commentId = $comment->id;
        $articleId = $comment->article_id;

        /** @var \Tests\TestCase $this */
        $this->deleteJson("api/articles/$articleId/comments/$commentId")
        ->assertStatus(401);
    });

    it('コメント作成者以外のユーザが削除しようとした場合403を返す', function () {
        $comment = Comment::first();
        $commentId = $comment->id;
        $articleId = $comment->article_id;
        $user = User::factory()->create();

        /** @var \Tests\TestCase $this */
        $this->actingAs($user)
            ->deleteJson("api/articles/$articleId/comments/$commentId")
            ->assertStatus(403);
    });

    it('存在しないコメントIDが指定された場合ステータス404で返す', function() {
        $comment = Comment::first();
        $commentId = $comment->id;
        $articleId = $comment->article_id;
        $user = User::find($comment->user_id);
        $articleId += 1;

        /** @var \Tests\TestCase $this */
        $this->actingAs($user)
        ->deleteJson("api/articles/$articleId/comments/$commentId")
        ->assertStatus(404);
    });

    it('例外が発生した場合ステータス500で返す', function () {
        $comment = Comment::first();
        $commentId = $comment->id;
        $articleId = $comment->article_id;
        $user = User::find($comment->user_id);

        /** @var \Tests\TestCase $this */
        $this->mock(CommentRepositoryInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('delete')
            ->andThrow(new Exception);
        });

       $this->actingAs($user)
        ->deleteJson("api/articles/$articleId/comments/$commentId")
        ->assertStatus(500);
    })->group('testB');
});


