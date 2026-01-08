<?php

declare(strict_types=1);

namespace App\Repositories\Article;

use App\Models\Article;
use App\Consts\CommonConst;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Exception;

class ArticleRepository implements ArticleRepositoryInterface
{
    public function search(int $page): LengthAwarePaginator
    {
        return Article::orderByRaw('created_at desc, id')->paginate(CommonConst::PER_PAGE);
    }

    public function find(int $id): ?Article
    {
        return Article::with('comments')->find($id);
    }

    public function create(array $data): Article
    {
        $article = Article::create($data);
        return $article->refresh();
    }

    public function update(array $data): ?Article
    {
        $id = $data['id'];
        $article = Article::find($id);

        if (is_null($article)) {
            return null;
        }

        $result = $article->update($data);

        // 更新失敗
        if ($result === 0) {
            throw new Exception(__('api.update.not_execute', compact('id')));
        }
        return $article->fresh();
    }

    public function delete(int $id): ?Article
    {
        return DB::transaction(function () use ($id) {
            $article = Article::find($id);
            if (is_null($article)) {
                return null;
            }

            $result = $article->delete();
            // 削除失敗
            if ($result === 0) {
                throw new Exception(__('api.delete.not_execute', compact('id')));
            }
            return $article->fresh();
        });
    }

    public function like(int $id): ?Article
    {
        $article = Article::find($id);
        if (is_null($article)) {
            return null;
        }

        $result = $article->increment('like');
        // 更新失敗
        if ($result === 0) {
            throw new Exception(__('api.update.not_execute', compact('id')));
        }

        return $article->fresh();
    }

}
