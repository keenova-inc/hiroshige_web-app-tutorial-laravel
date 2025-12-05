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

    public function show(string $id)
    {
        return "記事（ID:{$id}）を取得しました";
    }

    public function like(string $id)
    {
        return "記事（ID:{$id}）にいいねしました";
    }
}
