<?php

namespace App\Http\Requests\Article;

use App\Models\Article;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\FindRecord;
use App\Rules\Article\{ArticleTitle, ArticleUserName};
use Illuminate\Contracts\Validation\Validator;

class UpdateArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', new ArticleTitle],
            'content' => ['required'],
            'username' => ['required', new ArticleUserName],
            'id' => [new FindRecord(new Article)],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge(['id' => $this->route('id')]);
    }
}
