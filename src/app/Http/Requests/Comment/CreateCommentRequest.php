<?php declare(strict_types=1);

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Comment\CommentMessage;
use App\Rules\FindRecord;
use App\Models\Article;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Response;

class CreateCommentRequest extends FormRequest
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
            'message' => ['required', new CommentMessage()],
            'id' => [new FindRecord(new Article, trans('validation.attributes.article'))],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge(['id' => $this->route('id')]);
    }

    public function failedValidation(Validator $validator)
    {
        if($validator->errors()->has('id')) {
            abort(Response::HTTP_NOT_FOUND,
            $validator->errors()->get('id')[0]);
        }
    }

}
