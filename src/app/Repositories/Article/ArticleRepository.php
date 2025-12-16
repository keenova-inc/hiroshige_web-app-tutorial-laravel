<?php

declare(strict_types=1);

namespace App\Repositories\Article;

use App\Models\Article;
use App\Consts\CommonConst;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ArticleRepository implements ArticleRepositoryInterface
{
    public function search(int $page): ?LengthAwarePaginator
    {
        return Article::paginate(CommonConst::PER_PAGE);
    }

    public function find(int $id): ?Article
    {
        return Article::with('comments')->find($id);
    }

    public function create(array $data): ?Article
    {
        return Article::create($data);
    }

    public function update(array $data): ?Article
    {
        $id = $data['id'];
        Article::where('id', $id)->update($data);
        return Article::find($id);
    }

    public function delete(int $id): ?Article
    {
        return DB::transaction(function() use($id) {
            Article::findOrFail($id)->delete();
            return Article::withTrashed()->find($id);
        });
    }

    public function like(int $id): ?Article
    {
        Article::where('id', $id)->increment('like');
        return Article::find($id);
    }

}
