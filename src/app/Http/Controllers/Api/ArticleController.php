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

    public function index(SearchArticleRequest $request): JsonResponse
    {
        // \Log::debug(print_r($request->all(),true));
        $page = (int)$request->validated('page');

        $articles = $this->articleSvc->search($page);

        $status = $articles ?  Response::HTTP_OK : Response::HTTP_INTERNAL_SERVER_ERROR;
        return response()->json(compact('articles'), $status);
    }

    public function show(FindArticleRequest $request): JsonResponse
    {
        $id = (int)$request->validated('id');

        $article = $this->articleSvc->show($id);

        $status = $article ? Response::HTTP_OK : Response::HTTP_INTERNAL_SERVER_ERROR;
        return response()->json(compact('article'), $status);
    }

    public function create(CreateArticleRequest $request): JsonResponse
    {
        \Log::debug(print_r($request->validated(), true));

        $article = $this->articleSvc->create($request->validated());

        $attribute = __('validation.attributes.article');
        $message = $article ? __('api.create.success', ['id' => $article->id, 'attribute' => $attribute])
            : __('api.create.fail', ['attribute' => $attribute]);
        $status = $article ? Response::HTTP_OK : Response::HTTP_INTERNAL_SERVER_ERROR;

        return response()->json(compact('message', 'article'), $status);
    }

    // ここから。
    public function update(UpdateArticleRequest $request): JsonResponse
    {
        \Log::debug(print_r($request->validated(), true));

        $article = $this->articleSvc->update($request->validated());

        $id = $request->validated('id');
        $message = $article ? __('api.update.success', ['id' => $id]) : __('api.update.fail', ['id' => $id]);
        $status = $article ? Response::HTTP_OK : Response::HTTP_INTERNAL_SERVER_ERROR;
        return response()->json(compact('message', 'article'), $status);
    }

    public function delete(FindArticleRequest $request): JsonResponse
    {
        $id = (int)$request->validated('id');
        $article = $this->articleSvc->delete($id);
        // \Log::debug("RESULT ************ ");
        \Log::debug(print_r($article->toArray(), true));
        $message = $article ? __('api.delete.success', ['id' => $id]) : __('api.delete.fail', ['id' => $id]);
        $status = $article ? Response::HTTP_OK : Response::HTTP_INTERNAL_SERVER_ERROR;
        return response()->json(compact('message'), $status);
    }

    public function like(FindArticleRequest $request): JsonResponse
    {
        $id = (int)$request->validated('id');

        $article = $this->articleSvc->like($id);

        $message = $article ? __('api.update.success', ['id' => $id]) : __('api.update.fail', ['id' => $id]);
        $status = $article ? Response::HTTP_OK : Response::HTTP_INTERNAL_SERVER_ERROR;

        return response()->json(compact('message', 'article'), $status);
    }


}
