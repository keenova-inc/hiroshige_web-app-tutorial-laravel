<?php declare(strict_types=1);

namespace App\Repositories\Comment;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Comment;

interface CommentRepositoryInterface
{
    /**
     * コメント検索
     * @param array $data
     * @return LengthAwarePaginator
     */
    public function search(array $data): LengthAwarePaginator;

    /**
     * コメントを取得
     * @param array $data
     * @return Comment
     */
    public function find(array $data): Comment;

    /**
     * コメント作成
     * @param array $data
     * @return Comment
     */
    public function create(array $data): Comment;

    /**
     * コメント更新
     * @param array $data
     * @return Comment
     */
    public function update(array $data): Comment;

    /**
     * コメント削除
     * @param array $data
     * @return Comment
     */
    public function delete(array $data): Comment;
}
