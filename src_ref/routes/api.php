<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\GreetingController;

// Route::get('/greeting', function () {
//   return "Hello!";
// });

Route::get('/greeting', [GreetingController::class, 'greeting']);
