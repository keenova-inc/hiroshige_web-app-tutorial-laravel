<?php declare(strict_types=1);


namespace App\Services;

use App\Repositories\Comment\CommentRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CommentService {
    private $commentRepo;

    public function __construct(
        CommentRepositoryInterface $commentRepository
    ) {
        $this->commentRepo = $commentRepository;
    }

    public function search(array $data): array
    {
        try {
            $comment = $this->commentRepo->search($data);
            return ['comments' => $comment];
        } catch(Exception $e) {
            Log::error($e);
            return ['status' => Response::HTTP_INTERNAL_SERVER_ERROR, 'comments' => null];
        }
    }

    public function show(array $data): array
    {
        try {
            $comment = $this->commentRepo->find($data);
            if(is_null($comment)) {
                throw new Exception(__('api.not_exist',
                ['id' => $data['id'], 'attribute' => __('validation.attributes.message')]));
            }
            return ['comment' => $comment];
        } catch(Exception $e) {
            Log::error($e);

            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            if($e instanceof ModelNotFoundException) {
                $status = Response::HTTP_NOT_FOUND;
            }
            return ['status' => $status, 'comment' => null];
        }
    }

    public function create(array $data): array
    {
        try {
            $comment = $this->commentRepo->create($data);
            return ['comment' => $comment];

        } catch(Exception $e) {
            Log::error($e);

            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            if($e instanceof ModelNotFoundException) {
                $status = Response::HTTP_NOT_FOUND;
            }
            return ['status' => $status, 'comment' => null];
        }
    }

    public function update(array $data): array
    {
        try {
            $comment = $this->commentRepo->update($data);
            return ['comment' => $comment];
        } catch(Exception $e) {
            Log::error($e);

            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            if($e instanceof ModelNotFoundException) {
                $status = Response::HTTP_NOT_FOUND;
            }
            return ['status' => $status, 'comment' => null];
        }
    }

    public function delete(array $data): array
    {
        try {
            $comment = $this->commentRepo->delete($data);
            return ['comment' => $comment];
        } catch(Exception $e) {
            Log::error($e);

            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            if($e instanceof ModelNotFoundException) {
                $status = Response::HTTP_NOT_FOUND;
            }
            return ['status' => $status, 'comment' => null];
        }
    }

}
