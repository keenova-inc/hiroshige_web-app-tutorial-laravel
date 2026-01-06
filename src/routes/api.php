<?php

use App\Http\Controllers\Api\{ArticleController, CommentController,
    UserController};
use Illuminate\Support\Facades\Route;

/**
 * ログイン前API
 */

Route::prefix('/articles')->group(function () {
    // 記事
    Route::controller(ArticleController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/{id}/likes', 'like');

        // コメント
        Route::prefix('/{id}/comments')->group(function () {
            Route::controller(CommentController::class)->group(function () {
                Route::get('/', 'index');
                Route::get('/{comment_id}', 'show');
            });
        });

    });
});

// ユーザー
    Route::prefix('/users')->group(function () {
        Route::post('/', [UserController::class, 'create']);
    });
