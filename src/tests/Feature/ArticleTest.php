<?php

declare(strict_types=1);

use App\Consts\CommonConst;
use App\Models\{Article, Comment, User};
use Mockery\MockInterface;
use App\Repositories\Article\ArticleRepositoryInterface;
use Illuminate\Support\Str;

describe('記事一覧を取得', function () {
    it('正常完了', function () {
        // データを準備
        Article::factory()->withUser(CommonConst::PER_PAGE * 3, 1);
        /** @var \Tests\TestCase $this */
        $response = $this->getJson('api/articles');

        $response
        ->assertOk()
        ->assertJsonCount(CommonConst::PER_PAGE, 'articles.data');
    });

    it('記事が0件でも正常完了', function () {
        /** @var \Tests\TestCase $this */
        $response = $this->getJson('api/articles');
        $response
        ->assertOk()
        ->assertJsonCount(0, 'articles.data');
    });

    it('記事が作成日時の降順、作成日時が同じ場合にはid順で並んでいる', function () {
        // データを準備
        Article::factory()->withUser(CommonConst::PER_PAGE * 3, 1);
        $firstId = 3;
        $secondId = 10;

        // 指定のidの作成日時のみ未来にする
        Article::whereIn('id', [$firstId, $secondId])
        ->update(['created_at' => now()->addMinute()]);

        /** @var \Tests\TestCase $this */
        $response = $this->getJson('api/articles');
        $response
        ->assertOk()
        ->assertJsonCount(CommonConst::PER_PAGE, 'articles.data')
        ->assertJsonPath('articles.data.0.id', $firstId)
        ->assertJsonPath('articles.data.1.id', $secondId);
    });

    it('不正なページが指定されたらステータス400で返す', function () {
        /** @var \Tests\TestCase $this */
        $this->getJson('api/articles?page=0')
        ->assertStatus(400);
    });

    it('例外が発生した場合ステータス500で返す', function () {
        // モックを準備
        /** @var \Tests\TestCase $this */
        $this->mock(ArticleRepositoryInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('search')
            ->andThrow(new Exception());
        });

        $this->getJson('api/articles?page=1')
        ->assertStatus(500)
        ->assertJson(['message' => trans('api.cant_get')]);
    });

});

describe('記事を取得', function () {
    $commentCount = 2;

    beforeEach(function () use ($commentCount) {
        Comment::factory()->withUserAndArticle($commentCount, 2);
    });

    it('正常完了', function () use ($commentCount) {
        $articleId = Article::first()->id;

        /** @var \Tests\TestCase $this */
        $this->getJson('api/articles/' . $articleId)
        ->assertOk()
        ->assertJson(['article' => [
            'id' => $articleId,
            'comments' => [
                0 => ['article_id' => $articleId]
            ]
        ]])
        ->assertJsonCount($commentCount, 'article.comments');
    });

    it('存在しない記事IDが指定されたらステータス404で返す', function () {
        /** @var \Tests\TestCase $this */
        $this->getJson('api/articles/99999')
        ->assertStatus(404);
    });

    it('例外が発生した場合ステータス500で返す', function () {
        $articleId = Article::first()->id;

        // モックを準備
        /** @var \Tests\TestCase $this */
        $this->mock(ArticleRepositoryInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('find')
            ->andThrow(new Exception());
        });

        $this->getJson('api/articles/1')
        ->assertStatus(500)
        ->assertJson(['message' => trans(
            'api.not_exist',
            ['id' => $articleId, 'attribute' => __('validation.attributes.article')]
        )]);
    });
});

describe('記事を作成', function () {
    beforeEach(function () {
        User::factory()->create();
    });

    $createArray = [
        'title' => '記事のタイトル',
        'content' => '記事の内容',
    ];

    it('正常完了', function () use ($createArray) {
        $user = User::first();
        /** @var \Tests\TestCase $this */
        $response = $this->actingAs($user)
            ->postJson('api/articles', $createArray);

        $resultArray = [...$createArray, 'username' => $user->name];

        $response->assertCreated();
        $this->assertDatabaseHas('articles', $resultArray);
    });

    it('未ログインの場合ステータス401で返す', function () use ($createArray) {
        /** @var \Tests\TestCase $this */
        $this->postJson('api/articles', $createArray)
         ->assertStatus(401);
    });

    it('記事のタイトルが長すぎる場合422で返す', function () {
        $user = User::first();
        $validateArray = [
            'title' => Str::random(256),
            'content' => '記事の内容',
        ];
        /** @var \Tests\TestCase $this */
        $this->actingAs($user)
         ->postJson('api/articles', $validateArray)
         ->assertStatus(422);
    });

    it('例外が発生した場合ステータス500で返す', function () use ($createArray) {
        // モックを準備
        /** @var \Tests\TestCase $this */
        $this->mock(ArticleRepositoryInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('create')
            ->andThrow(new Exception());
        });

        $this->actingAs(User::first())
         ->postJson('api/articles', $createArray)
         ->assertStatus(500);
    });
});

describe('記事を更新', function () {
    beforeEach(function () {
        Article::factory()->withUser(1);
    });

    $updateArray = [
        'title' => '記事のタイトル（更新後）',
        'content' => '記事の内容（更新後）',
    ];

    it('正常完了', function () use ($updateArray) {
        $article = Article::first();
        $articleId = $article->id;
        $userId = $article->user_id;

        $user = User::find($userId);
        User::where('id', $userId)->update(['name' => '更新後の名前']);

        /** @var \Tests\TestCase $this */
        $response = $this->actingAs($user)
            ->putJson('api/articles/' . $articleId, $updateArray);

        $resultArray = [...$updateArray,
            'username' => $user->name, 'user_id' => $user->id,]; // 記事作成時の名前であること

        $response->assertOk();
        $this->assertDatabaseHas('articles', $resultArray);
    });

    it('存在しない記事IDが指定された場合ステータス404で返す', function () {
        $article = Article::first();
        $user = User::find($article->user_id);
        /** @var \Tests\TestCase $this */
        $this->actingAs($user)
        ->putJson('api/articles/99999')
        ->assertStatus(404);
    });


    it('未ログインの場合ステータス401で返す', function () use ($updateArray) {
        $article = Article::first();
        $articleId = $article->id;

        /** @var \Tests\TestCase $this */
        $this->putJson('api/articles/' . $articleId, $updateArray)
        ->assertStatus(401);
    });

    it('記事作成者以外のユーザが更新しようとした場合403を返す', function () use ($updateArray) {
        $article = Article::first();
        $articleId = $article->id;
        $user = User::factory()->create();

        /** @var \Tests\TestCase $this */
        $this->actingAs($user)
            ->putJson('api/articles/' . $articleId, $updateArray)
            ->assertStatus(403);
    });

    it('記事のタイトルが長すぎる場合422で返す', function () use ($updateArray) {
        $article = Article::first();
        $articleId = $article->id;
        $user = User::find($article->user_id);

        $validateArray = [
            'title' => Str::random(256),
            'content' => '記事の内容',
            'username' => $user->username,
        ];
        /** @var \Tests\TestCase $this */
        $this->actingAs($user)
         ->putJson("api/articles/$articleId", $validateArray)
         ->assertStatus(422);
    });

    it('例外が発生した場合ステータス500で返す', function () use ($updateArray) {
        $article = Article::first();
        $articleId = $article->id;
        $user = User::find($article->user_id);

        /** @var \Tests\TestCase $this */
        $this->mock(ArticleRepositoryInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('update')
            ->andThrow(new Exception());
        });

        $this->actingAs($user)
         ->putJson('api/articles/' . $articleId, $updateArray)
         ->assertStatus(500);
    });
});

describe('記事を削除', function () {
    beforeEach(function () {
        Article::factory()->withUser(1);
    });

    it('正常完了', function () {
        $article = Article::first();
        $articleId = $article->id;
        $user = User::find($article->user_id);

        /** @var \Tests\TestCase $this */
        $response = $this->actingAs($user)
            ->deleteJson('api/articles/' . $articleId)
            ->assertOk();
        $this->assertSoftDeleted('articles', $article->toArray());
    });

    it('未ログインの場合ステータス401で返す', function () {
        $article = Article::first();
        $articleId = $article->id;

        /** @var \Tests\TestCase $this */
        $this->deleteJson('api/articles/' . $articleId)
        ->assertStatus(401);
    })->group('Test');

    it('記事作成者以外のユーザが削除しようとした場合403を返す', function () {
        $article = Article::first();
        $articleId = $article->id;
        $user = User::factory()->create();

        /** @var \Tests\TestCase $this */
        $this->actingAs($user)
            ->deleteJson('api/articles/' . $articleId)
            ->assertStatus(403);
    });

    it('存在しない記事IDが指定された場合ステータス404で返す', function () {
        $article = Article::first();
        $user = User::find($article->user_id);
        /** @var \Tests\TestCase $this */
        $this->actingAs($user)
        ->deleteJson('api/articles/99999')
        ->assertStatus(404);
    });

    it('例外が発生した場合ステータス500で返す', function () {
        $article = Article::first();
        $articleId = $article->id;
        $user = User::find($article->user_id);

        /** @var \Tests\TestCase $this */
        $this->mock(ArticleRepositoryInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('delete')
            ->andThrow(new Exception());
        });

        $this->actingAs($user)
         ->deleteJson('api/articles/' . $articleId)
         ->assertStatus(500);
    });
});

describe('記事に「いいね」をする', function () {
    beforeEach(function () {
        Article::factory()->withUser(1);
    });

    it('正常完了', function () {
        $article = Article::first();
        $articleId = $article->id;
        $article['like'] += 1;
        /** @var \Tests\TestCase $this */
        $this->postJson("api/articles/$articleId/likes")
            ->assertOk();
        $this->assertDatabaseHas('articles', $article->toArray());
    });

    it('存在しない記事IDが指定された場合ステータス404で返す', function () {
        /** @var \Tests\TestCase $this */
        $this->postJson("api/articles/abc/likes")
            ->assertStatus(404);
    });

    it('例外が発生した場合ステータス500で返す', function () {
        $article = Article::first();
        $articleId = $article->id;

        /** @var \Tests\TestCase $this */
        $this->mock(ArticleRepositoryInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('like')
            ->andThrow(new Exception());
        });

        $this->postJson("api/articles/$articleId/likes")
        ->assertStatus(500);
    })->group('testB');
});
