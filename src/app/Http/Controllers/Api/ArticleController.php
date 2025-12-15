<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->query('page', 1);
        if (! preg_match('/^[1-9][0-9]*$/u', $page)) {
            return response()->json(['message' => 'Invalid parameter.'], 400);
        }

        $articles = Article::paginate();

        return response()->json($articles);
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'username' => 'required|string|max:50',
        ]);

        Article::create($validatedData);

        return response()->json(['message' => '登録完了しました'], 200);
    }

    public function show(int $id)
    {
        $article = Article::with(['comments' => function ($query) {
            $query->limit(1);
        }])->find($id);

        if (is_null($article)) {
            $article = ['message' => 'Article not found.'];
        }
        return response()->json($article, 404);
    }

    public function like(int $id)
    {
        $article = Article::find($id);

        if (is_null($article)) {
            return response()->json(['message' => 'Article not found.'], 404);
        }
        $article->increment('like');

        return response()->json([
            'message' => "Article {$id} liked successfully.",
            'article_id' => $id,
            'like' => $article->like,
        ]);
    }
}
