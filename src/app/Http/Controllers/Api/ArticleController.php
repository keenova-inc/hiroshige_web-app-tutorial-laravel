<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Article\{SearchArticleRequest, CreateArticleRequest,
    UpdateArticleRequest, FindArticleRequest};
use App\Models\Article;
use App\Services\ArticleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ArticleController extends Controller
{
    private $articleSvc;

    public function __construct(
        ArticleService $articleService
    )
    {
        $this->articleSvc = $articleService;
    }

    /**
     * 記事一覧を取得
     */
    public function index(SearchArticleRequest $request): JsonResponse
    {
        $page = $request->validated('page') ?? 1;

        $data = $this->articleSvc->search((int)$page);
        $articles = $data['articles'];
        $status = $data['status'] ?? Response::HTTP_OK;

        return response()->json(compact('articles'), $status);
    }

    /**
     * 記事を取得
     */
    public function show(FindArticleRequest $request): JsonResponse
    {
        $id = (int)$request->validated('id');

        $data = $this->articleSvc->show($id);
        $article = $data['article'];
        $status = $data['status'] ?? Response::HTTP_OK;

        return response()->json(compact('article'), $status);
    }

    public function create(CreateArticleRequest $request): JsonResponse
    {
        // \Log::debug(print_r($request->validated(), true));

        $data = $this->articleSvc->create($request->validated());
        $article = $data['article'];
        $status = $data['status'] ?? Response::HTTP_CREATED;

        $attribute = __('validation.attributes.article');
        $message = $article ? __('api.create.success', ['id' => $article->id, 'attribute' => $attribute])
            : __('api.create.fail', ['attribute' => $attribute]);

        return response()->json(compact('message', 'article'), $status);
    }

    public function update(UpdateArticleRequest $request): JsonResponse
    {
        // \Log::debug(print_r($request->validated(), true));
        $id = $request->validated('id');

        $data = $this->articleSvc->update($request->validated());
        $article = $data['article'];
        $status = $data['status'] ?? Response::HTTP_OK;
        $message = is_null($article) ? __('api.update.fail', ['id' => $id]) :  __('api.update.success', ['id' => $id]);

        return response()->json(compact('message', 'article'), $status);
    }

    public function delete(FindArticleRequest $request): JsonResponse
    {
        $id = (int)$request->validated('id');

        $data = $this->articleSvc->delete($id);
        $article = $data['article'];
        $status = $data['status'] ?? Response::HTTP_OK;
        $message = is_null($article) ? __('api.delete.fail', ['id' => $id]) : __('api.delete.success', ['id' => $id]);

        return response()->json(compact('message'), $status);
    }

    public function like(FindArticleRequest $request): JsonResponse
    {
        $id = (int)$request->validated('id');

        $data = $this->articleSvc->like($id);
        $article = $data['article'];
        $status = $data['status'] ?? Response::HTTP_OK;
        $message = is_null($article) ? __('api.update.fail', ['id' => $id]) :  __('api.update.success', ['id' => $id]);

        return response()->json(compact('message', 'article'), $status);
    }


}
