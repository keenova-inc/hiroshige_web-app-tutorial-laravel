<?php

declare(strict_types=1);

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use App\Models\Comment;

class DeleteCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $commentId = $this->comment_id;
        $articleId = $this->id;
        $comment = Comment::where('id', $commentId)
            ->where('article_id', $articleId)->first();

        if (is_null($comment)) {
            abort(Response::HTTP_NOT_FOUND, trans(
                'api.not_exist',
                ['id' => $commentId, 'attribute' => __('validation.attributes.message')]
            ));
        }

        return $this->user()->id === $comment->user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => [],
            'comment_id' => [],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge(['id' => $this->route('id')]);
        $this->merge(['comment_id' => $this->route('comment_id')]);
    }

    protected function failedAuthorization()
    {
        abort(
            Response::HTTP_FORBIDDEN,
            trans('api.not_authorized', ['id' => $this->route('comment_id')])
        );
    }

}
