<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\GreetingController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\CommentController;

// Route::get('/greeting', function () {
//   return "Hello!";
// });

Route::get('/greeting', [GreetingController::class, 'greeting']);

// 記事
Route::get('/articles', [ArticleController::class, 'index']);
Route::post('/articles', [ArticleController::class, 'store']);
Route::get('/articles/{id}', [ArticleController::class, 'show']);
Route::post('/articles/{id}/likes', [ArticleController::class, 'like']);

// // コメント
Route::post('/articles/{article}/comments', [CommentController::class, 'store']);
