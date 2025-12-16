<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function create(Article $article, Request $request)
    {
        // \Log::debug(print_r($article->toArray(), true));
        $validatedData = $request->validate([
        'message' => 'required|string|max:500',
        ]);

        $article->comments()->create($validatedData);

        return response()->json(['message' => 'Comment created successfully.']);
    }
}
