<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\{CreateCommentRequest, FindCommentRequest,
    UpdateCommentRequest, DeleteCommentRequest};
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
     * 記事のコメント一覧を取得
     * @param SearchCommentRequest $request
     * @return JsonResponse
     */
    public function index(SearchCommentRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $validatedData['page'] = $validatedData['page'] ?? 1;
        $data = $this->commentSvc->search($validatedData);
        $comments = $data['comments'];
        $status = $data['status'] ?? Response::HTTP_OK;
        $message = trans('api.cant_get');
        $resArray = is_null($comments) ? compact('message') : compact('comments');

        return response()->json($resArray, $status);
    }

    /**
     * 記事のコメントを取得
     * @param FindCommentRequest $request
     * @return JsonResponse
     */
    public function show(FindCommentRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $data = $this->commentSvc->show($validatedData);
        $comment = $data['comment'];
        $status = $data['status'] ?? Response::HTTP_OK;
        $attribute = __('validation.attributes.message');
        $message = is_null($comment) ? __('api.not_exist',
        ['id' => $validatedData['comment_id'], 'attribute' => $attribute]) : '';
        $resArray = is_null($comment) ? compact('message') : compact('comment');

        return response()->json($resArray, $status);
    }

    /**
     * 記事のコメントを作成
     * @param CreateCommentRequest $request
     * @return JsonResponse
     */
    public function create(CreateCommentRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $validatedData['user_id'] = $request->user()->id;

        $data = $this->commentSvc->create($validatedData);
        $comment = $data['comment'];
        $status = $data['status'] ?? Response::HTTP_CREATED;
        $attribute = __('validation.attributes.message');
        $message = is_null($comment) ? __('api.create.fail', ['attribute' => $attribute])
        : __('api.create.success', ['id' => $comment->id, 'attribute' => $attribute]);
        $resArray = is_null($comment) ? compact('message') : compact('comment', 'message');

        return response()->json($resArray, $status);
    }

    /**
     * 記事のコメントを更新
     * @param UpdateCommentRequest $request
     * @return JsonResponse
     */
    public function update(UpdateCommentRequest $request): JsonResponse
    {
        $commentId = $request->validated('comment_id');

        $data = $this->commentSvc->update($request->validated());
        $comment = $data['comment'];
        $status = $data['status'] ?? Response::HTTP_OK;
        $message = is_null($comment) ?  __('api.update.fail', ['id' => $commentId])
        : __('api.update.success', ['id' => $commentId]);
        $resArray = is_null($comment) ? compact('message') : compact('comment', 'message');

        return response()->json($resArray, $status);
    }

    /**
     * 記事のコメントを削除
     * @param FindCommentRequest $request
     * @return JsonResponse
     */
    public function delete(DeleteCommentRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $data = $this->commentSvc->delete($validatedData);
        $comment = $data['comment'];
        $status = $data['status'] ?? Response::HTTP_OK;
        $message = is_null($comment) ? __('api.delete.fail', ['id' => $validatedData['comment_id']])
        : __('api.delete.success', ['id' => $validatedData['comment_id']]);
        $resArray = is_null($comment) ? compact('message') : compact('comment', 'message');

        return response()->json($resArray, $status);
    }
}
