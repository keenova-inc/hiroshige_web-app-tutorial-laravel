<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Requests\Comment\{CreateCommentRequest, FindCommentRequest, UpdateCommentRequest};
use App\Services\CommentService;
use Illuminate\Http\Response;
use App\Http\Requests\Comment\SearchCommentRequest;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    private $commentSvc;

    public function __construct(
        CommentService $commentService
    )
    {
        $this->commentSvc = $commentService;
    }

    /**
     * コメント一覧取得
     */
    public function index(SearchCommentRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $validatedData['page'] = $validatedData['page'] ?? 1;
        $data = $this->commentSvc->search($validatedData);
        $comments = $data['comments'];
        $status = $data['status'] ?? Response::HTTP_OK;

        return response()->json(compact('comments'), $status);
    }

    /**
     * コメント詳細
     */
    public function show(FindCommentRequest $request): JsonResponse
    {
        $data = $this->commentSvc->show($request->validated());
        $comment = $data['comment'];
        $status = $data['status'] ?? Response::HTTP_OK;

        return response()->json(compact('comment'), $status);
    }

    /**
     * コメント登録
     */
    public function create(CreateCommentRequest $request): JsonResponse
    {
        // \Log::debug(print_r($request->validated(), true));

        $data = $this->commentSvc->create($request->validated());
        $comment = $data['comment'];
        $status = $data['status'] ?? Response::HTTP_CREATED;
        $attribute = __('validation.attributes.message');
        $message = is_null($comment) ? __('api.create.fail', ['attribute' => $attribute])
        : __('api.create.success', ['id' => $comment->id, 'attribute' => $attribute]);

        return response()->json(compact('message', 'comment'), $status);
    }

    /**
     * コメント更新
     */
    public function update(UpdateCommentRequest $request): JsonResponse
    {
        $commentId = $request->validated('comment_id');

        $data = $this->commentSvc->update($request->validated());
        $comment = $data['comment'];
        $status = $data['status'] ?? Response::HTTP_OK;
        $message = is_null($comment) ?  __('api.update.fail', ['id' => $commentId])
        : __('api.update.success', ['id' => $commentId]);

        return response()->json(compact('message', 'comment'), $status);
    }

    /**
     * コメント削除
     */
    public function delete(FindCommentRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $data = $this->commentSvc->delete($validatedData);
        $comment = $data['comment'];
        $status = $data['status'] ?? Response::HTTP_OK;
        $message = is_null($comment) ? __('api.delete.fail', ['id' => $validatedData['comment_id']])
        : __('api.delete.success', ['id' => $validatedData['comment_id']]);

        return response()->json(compact('message'), $status);
    }
}
