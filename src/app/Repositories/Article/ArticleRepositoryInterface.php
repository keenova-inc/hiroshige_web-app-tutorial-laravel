<?php declare(strict_types=1);

namespace App\Repositories\Article;

use App\Models\Article;
use Illuminate\Pagination\LengthAwarePaginator;

interface ArticleRepositoryInterface
{
    /**
     * 記事検索
     * @param int $page
     * @return LengthAwarePaginator
     */
    public function search(int $page): LengthAwarePaginator;

    /**
     * 記事を取得
     * @param int $id
     * @return ?Article
     */
    public function find(int $id): ?Article;

    /**
     * 記事作成
     * @param array $data
     * @return Article
     */
    public function create(array $data): Article;

    /**
     * 記事更新
     * @param array $data
     * @return ?Article
     */
    public function update(array $data): ?Article;

    /**
     * 記事削除
     * @param int $id
     * @return ?Article
     */
    public function delete(int $id): ?Article;

    /**
     * 記事の「いいね」をincrement
     * @param int $id
     * @return ?Article
     */
    public function like(int $id): ?Article;
}
