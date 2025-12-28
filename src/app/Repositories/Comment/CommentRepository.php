<?php declare(strict_types=1);

namespace App\Repositories\Comment;

use App\Models\{Article,Comment};
use Illuminate\Pagination\LengthAwarePaginator;
use App\Consts\CommonConst;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CommentRepository implements CommentRepositoryInterface
{
    public function search(array $data): LengthAwarePaginator
    {
        $page = $data['page'];
        return Comment::where('article_id', $data['id'])->paginate(
            CommonConst::PER_PAGE, ['*'], 'page', $page);
    }

    public function find($data): ?Comment
    {
        $commentId = (int)$data['comment_id'];

        return Comment::where('article_id', (int)$data['id'])
        ->where('id', $commentId)
        ->with('article')
        ->first();
    }

    public function create(array $data): ?Comment
    {
        $articleId = $data['id'];
        unset($data['id']);

        $article = Article::find($articleId);
        if(is_null($article)) {
            return null;
        }

        return $article->comments()->create($data);
    }

    public function update(array $data): ?Comment
    {
        // \Log::debug(print_r($data, true));
        $id = $data['id'];
        $commentId = $data['comment_id'];

        $comment = Comment::where('article_id', $id)
        ->where('id', $commentId)->first();
        if(is_null($comment)) {
            return null;
        }

        // 更新
        $result = $comment->update($data);
        // 更新失敗
        if($result === 0) {
            throw new Exception(__('api.update.not_execute', compact('id')));
        }

        return $comment->fresh();
    }

    public function delete(array $data): ?Comment
    {
        return DB::transaction(function() use($data) {
            $comment = Comment::where('id', $data['comment_id'])
            ->where('article_id', $data['id'])
            ->first();

            if(is_null($comment)) {
                return null;
            }

            $result = $comment->delete();
            // 削除失敗
            if($result === 0) {
                throw new Exception(__('api.delete.not_execute', compact('id')));
            }
            return $comment->fresh();
        });
    }

}
