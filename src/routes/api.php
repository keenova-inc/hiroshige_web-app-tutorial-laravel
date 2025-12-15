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
    Route::controller(ArticleController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/', 'create');
        Route::post('/{id}/likes', 'like');
    });
    Route::post('/{article}/comments', [CommentController::class, 'create']);
});
