<?php

declare(strict_types=1);

namespace App\Http\Requests\Article;

use App\Models\Article;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Article\ArticleTitle;
use Illuminate\Http\Response;

class UpdateArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $articleId = $this->id;
        $article = Article::find($articleId);
        if (is_null($article)) {
            abort(Response::HTTP_NOT_FOUND, trans(
                'api.not_exist',
                ['id' => $articleId, 'attribute' => __('validation.attributes.article')]
            ));
        }

        return $this->user()->id === $article->user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', new ArticleTitle()],
            'content' => ['required'],
            'id' => [],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge(['id' => $this->route('id')]);
    }

    protected function failedAuthorization()
    {
        abort(
            Response::HTTP_FORBIDDEN,
            trans('api.not_authorized', ['id' => $this->route('id')])
        );
    }


}
