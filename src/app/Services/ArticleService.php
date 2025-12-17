<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Article;
use App\Repositories\Article\ArticleRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;

class ArticleService {
    private $articleRepo;

    public function __construct(
        ArticleRepositoryInterface $articleRepository
    ) {
        $this->articleRepo = $articleRepository;
    }

    public function search(int $page): array
    {
        try {
            $articles = $this->articleRepo->search($page);
            return ['articles' => $articles];
        } catch(Exception $e) {
            Log::error($e);
            return ['status' => Response::HTTP_INTERNAL_SERVER_ERROR, 'articles' => null];
        }
    }

    public function show(int $id): array
    {
        try {
            $article = $this->articleRepo->find($id);
            return ['article' => $article];
        } catch(Exception $e) {
            Log::error($e);

            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            if($e instanceof ModelNotFoundException) {
                $status = Response::HTTP_NOT_FOUND;
            }
            return ['status' => $status, 'article' => null];
        }
    }

    public function create(array $data): array
    {
        try {
            $article = $this->articleRepo->create($data);
            return ['article' => $article];
        } catch(Exception $e) {
            Log::error($e);
            return ['status' => Response::HTTP_INTERNAL_SERVER_ERROR, 'article' => null];
        }
    }

    public function update(array $data): array
    {
        try {
            $article = $this->articleRepo->update($data);
            return ['article' => $article];
        } catch(Exception $e) {
            Log::error($e);

            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            if($e instanceof ModelNotFoundException) {
                $status = Response::HTTP_NOT_FOUND;
            }
            return ['status' => $status, 'article' => null];
        }
    }

    public function delete(int $id): array
    {
        try {
            $article = $this->articleRepo->delete($id);
            return ['article' => $article];
        } catch(Exception $e) {
            Log::error($e);

            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            if($e instanceof ModelNotFoundException) {
                $status = Response::HTTP_NOT_FOUND;
            }
            return ['status' => $status, 'article' => null];
        }
    }

    public function like(int $id): array
    {
        try {
            $article = $this->articleRepo->like($id);
            return ['article' => $article];
        } catch(Exception $e) {
            Log::error($e);

            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            if($e instanceof ModelNotFoundException) {
                $status = Response::HTTP_NOT_FOUND;
            }
            return ['status' => $status, 'article' => null];
        }
    }
}
