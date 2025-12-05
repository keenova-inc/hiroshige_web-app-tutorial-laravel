<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->query('page', 1);

        if (!is_numeric($page) || $page < 1) {
            return response("Invalid parameter.", 400);
        }

        return "記事一覧を取得しました（page={$page}）";
    }

    public function store()
    {
        return "記事を投稿しました";
    }

    // 記事の取得
    public function show(string $id)
    {
        $article = Article::with('comments')->find($id);

        if (!$article) {
            return response()->json([
                "message" => "Article not found."
            ], 404);
        }

        return response()->json($article);
    }

    public function like(string $id)
    {
        return "記事（ID:{$id}）にいいねしました";
    }
}
