<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\GreetingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/greeting', [GreetingController::class, 'greeting']);

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('/articles')->group(function () {
    // 記事
    Route::controller(ArticleController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/', 'create');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'delete');
        Route::post('/{id}/likes', 'like');

        // コメント
        Route::prefix('/{id}/comments')->group(function () {
            Route::controller(CommentController::class)->group(function () {
                Route::get('/', 'index');
                Route::get('/{comment_id}', 'show');
                Route::post('/', 'create');
                Route::put('/{comment_id}', 'update');
                Route::delete('/{comment_id}', 'delete');
            });
        });

    });
});
