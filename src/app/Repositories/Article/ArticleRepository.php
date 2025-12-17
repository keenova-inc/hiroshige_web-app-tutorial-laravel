<?php

declare(strict_types=1);

namespace App\Repositories\Article;

use App\Models\Article;
use App\Consts\CommonConst;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Exception;
use Error;
use PDOException;
use Illuminate\Database\QueryException;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ArticleRepository implements ArticleRepositoryInterface
{
    public function search(int $page): LengthAwarePaginator
    {
        return Article::paginate(CommonConst::PER_PAGE);
    }

    public function find(int $id): Article
    {
        $article = Article::with('comments')->find($id);
        if(is_null($article)) {
            throw new ModelNotFoundException(__('api.not_exist',
            ['id' => $id, 'attribute' => __('validation.attributes.article')]));
        }
        return $article;
    }

    public function create(array $data): Article
    {
        return Article::create($data);
    }

    public function update(array $data): Article
    {
        $id = $data['id'];
        $article = Article::find($id);
        if(is_null($article)) {
            throw new ModelNotFoundException(__('api.not_exist',
            ['id' => $id, 'attribute' => __('validation.attributes.article')]));
        }

        $result = $article->update($data);
        // 更新失敗
        if($result === 0) {
            throw new Exception(__('api.update.not_execute', compact('id')));
        }
        return $article->reflesh();
    }

    public function delete(int $id): Article
    {
        return DB::transaction(function() use($id) {
            $article = Article::find($id);
            if(is_null($article)) {
                throw new ModelNotFoundException(__('api.not_exist',
                ['id' => $id, 'attribute' => __('validation.attributes.article')]));
            }

            $result = $article->delete();
            // 削除失敗
            if($result === 0) {
                throw new Exception(__('api.delete.not_execute', compact('id')));
            }
            return $article->refresh();
        });
    }

    public function like(int $id): Article
    {
        $article = Article::find($id);
        if(is_null($article)) {
            throw new ModelNotFoundException(__('api.not_exist',
            ['id' => $id, 'attribute' => __('validation.attributes.article')]));
        }

        $result = $article->increment('like');
        // 更新失敗
        if($result === 0) {
            throw new Exception(__('api.update.not_execute', compact('id')));
        }

        return $article->refresh();
    }

}
