<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\Comment\CommentRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Util\HandleException;

class CommentService
{
    private $commentRepo;

    public function __construct(
        CommentRepositoryInterface $commentRepository
    ) {
        $this->commentRepo = $commentRepository;
    }

    /**
     * コメント検索
     * @param array $data
     * @return array
     */
    public function search(array $data): array
    {
        try {
            $comment = $this->commentRepo->search($data);
            return ['comments' => $comment];
        } catch (Exception $e) {
            Log::error($e);
            return ['status' => Response::HTTP_INTERNAL_SERVER_ERROR, 'comments' => null];
        }
    }

    /**
     * コメントを取得
     * @param array $data
     * @return array
     */
    public function show(array $data): array
    {
        try {
            $comment = $this->commentRepo->find($data);
            if (is_null($comment)) {
                throw new ModelNotFoundException(__(
                    'api.not_exist',
                    ['id' => $data['comment_id'], 'attribute' => __('validation.attributes.message')]
                ));
            }
            return ['comment' => $comment];
        } catch (Exception $e) {
            Log::error($e);
            return ['status' => HandleException::decideStatus($e), 'comment' => null];
        }
    }

    /**
    * コメント作成
    * @param array $data
    * @return array
    */
    public function create(array $data): array
    {
        try {
            $comment = $this->commentRepo->create($data);
            if (is_null($comment)) {
                throw new ModelNotFoundException(__(
                    'api.not_exist',
                    ['id' => $data['id'], 'attribute' => __('validation.attributes.article')]
                ));
            }
            return ['comment' => $comment];
        } catch (Exception $e) {
            Log::error($e);
            return ['status' => HandleException::decideStatus($e), 'comment' => null];
        }
    }

    /**
     * コメント更新
     * @param array $data
     * @return array
     */
    public function update(array $data): array
    {
        try {
            $comment = $this->commentRepo->update($data);
            if (is_null($comment)) {
                throw new ModelNotFoundException(__(
                    'api.not_exist',
                    ['id' => $data['comment_id'], 'attribute' => __('validation.attributes.message')]
                ));
            }
            return ['comment' => $comment];
        } catch (Exception $e) {
            Log::error($e);
            return ['status' => HandleException::decideStatus($e), 'comment' => null];
        }
    }

    /**
     * コメント削除
     * @param array $data
     * @return array
     */
    public function delete(array $data): array
    {
        try {
            $comment = $this->commentRepo->delete($data);
            if (is_null($comment)) {
                throw new ModelNotFoundException(__(
                    'api.not_exist',
                    ['id' => $data['comment_id'], 'attribute' => __('validation.attributes.message')]
                ));
            }
            return ['comment' => $comment];
        } catch (Exception $e) {
            Log::error($e);
            return ['status' => HandleException::decideStatus($e), 'comment' => null];
        }
    }
}
