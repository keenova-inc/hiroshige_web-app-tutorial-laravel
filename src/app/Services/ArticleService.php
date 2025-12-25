<?php declare(strict_types=1);

namespace App\Services;

use App\Repositories\Article\ArticleRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Util\HandleException;

class ArticleService {
    private $articleRepo;

    public function __construct(
        ArticleRepositoryInterface $articleRepository
    ) {
        $this->articleRepo = $articleRepository;
    }

    /**
     * 記事検索
     * @param int $page
     * @return array
     */
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

     /**
     * 記事を取得
     * @param int $id
     * @return array
     */
    public function show(int $id): array
    {
        try {
            $article = $this->articleRepo->find($id);
            if(is_null($article)) {
                throw new ModelNotFoundException(__('api.not_exist',
                ['id' => $id, 'attribute' => __('validation.attributes.article')]));
            }

            return ['article' => $article];
        } catch(Exception $e) {
            Log::error($e);
            return ['status' => HandleException::decideStatus($e), 'article' => null];
        }
    }

     /**
     * 記事作成
     * @param array $data
     * @return array
     */
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

    /**
     * 記事更新
     * @param array $data
     * @return array
     */
    public function update(array $data): array
    {
        try {
            $article = $this->articleRepo->update($data);
            if(is_null($article)) {
                throw new ModelNotFoundException(__('api.not_exist',
                ['id' => $data['id'], 'attribute' => __('validation.attributes.article')]));
            }

            return ['article' => $article];
        } catch(Exception $e) {
            Log::error($e);
            return ['status' => HandleException::decideStatus($e), 'article' => null];
        }
    }

    /**
     * 記事削除
     * @param int $id
     * @return array
     */
    public function delete(int $id): array
    {
        try {
            $article = $this->articleRepo->delete($id);
            if(is_null($article)) {
                throw new ModelNotFoundException(__('api.not_exist',
                ['id' => $id, 'attribute' => __('validation.attributes.article')]));
            }

            return ['article' => $article];
        } catch(Exception $e) {
            Log::error($e);
            return ['status' => HandleException::decideStatus($e), 'article' => null];
        }
    }

    /**
     * 記事の「いいね」をincrement
     * @param int $id
     * @return array
     */
    public function like(int $id): array
    {
        try {
            $article = $this->articleRepo->like($id);
            if(is_null($article)) {
                throw new ModelNotFoundException(__('api.not_exist',
                ['id' => $id, 'attribute' => __('validation.attributes.article')]));
            }

            return ['article' => $article];
        } catch(Exception $e) {
            Log::error($e);
            return ['status' => HandleException::decideStatus($e), 'article' => null];
        }
    }
}
