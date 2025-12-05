<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        return "記事一覧を取得しました";
    }

    public function store()
    {
        return "記事を投稿しました";
    }

    public function show()
    {
        return "記事（ID:1）を取得しました";
    }

    public function like()
    {
        return "記事（ID:1）にいいねしました";
    }
}
