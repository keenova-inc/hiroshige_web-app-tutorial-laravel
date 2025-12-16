<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Article;
use App\Repositories\Article\ArticleRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ArticleService {
    private $articleRepo;

    public function __construct(
        ArticleRepositoryInterface $articleRepository
    ) {
        $this->articleRepo = $articleRepository;
    }

    public function search(int $page): ?LengthAwarePaginator
    {
        try {
            $article = $this->articleRepo->search($page);
        } catch(Exception $e) {
            Log::error($e);
            return null;
        }
        return $article;
    }

    public function show(int $id): ?Article
    {
        try {
            $article = $this->articleRepo->find($id);
            if(is_null($article)) {
                throw new Exception(__('api.not_exist',
                ['id' => $id, 'attribute' => __('validation.attributes.article')]));
            }
        } catch(Exception $e) {
            Log::error($e);
            return null;
        }
        return $article;
    }

    public function create(array $data): ?Article
    {
        try {
            $article = $this->articleRepo->create($data);
            return $article;
        } catch(Exception $e) {
            // Log::debug("@@@@@@ Exception  Exception Exception@@@@@@");
            Log::error($e);
            return null;
        }
    }

    public function update(array $data): ?Article
    {
        try {
            $article = $this->articleRepo->update($data);
            if($article === null) {
                throw new Exception(__('api.not_exist',
                ['id' => $data['id'], 'attribute' => __('validation.attributes.article')]));
            }

            return $article;
        } catch(Exception $e) {
            // Log::debug("@@@@@@ Exception  Exception Exception@@@@@@");
            Log::error($e);
            return null;
        }
    }

    public function delete(int $id): ?Article
    {
        try {
            return $this->articleRepo->delete($id);
        } catch(Exception $e) {
            Log::error($e);
            return null;
        }
    }

    public function like(int $id): ?Article
    {
        try {
            return $this->articleRepo->like($id);
        } catch(Exception $e) {
            Log::error($e);
            return null;
        }
    }


}
