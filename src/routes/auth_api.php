<?php

use App\Http\Controllers\Api\{ArticleController, AuthController, CommentController};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/**
 * ログイン後API
 */
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/test', function () {
        return "Hello!";
    });
    Route::get('/login-check', function (Request $request) {
        return $request->user();
    });

    Route::prefix('/articles')->group(function () {
        // 記事
        Route::controller(ArticleController::class)->group(function () {
            Route::post('/', 'create');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'delete');

            // コメント
            Route::prefix('/{id}/comments')->group(function () {
                Route::controller(CommentController::class)->group(function () {
                    Route::post('/', 'create');
                    Route::put('/{comment_id}', 'update');
                    Route::delete('/{comment_id}', 'delete');
                });
            });
        });
    });

    // // ログアウト（Cookieでの認証時はFortifyのPOST /logoutを使用）
    // Route::post('/logout',  [AuthController::class, 'logout']);
});
