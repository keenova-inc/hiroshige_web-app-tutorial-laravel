<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class GreetingController extends Controller
{
    public function greeting()
    {
        Log::debug('EXIST: '.class_exists(\App\Repositories\Article\ArticleRepositoryInterface::class));
        return 'Hello Controller';
    }
}
