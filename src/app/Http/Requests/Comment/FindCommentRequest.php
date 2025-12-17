<?php declare(strict_types=1);

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\FindRecord;
use App\Models\Article;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class FindCommentRequest extends FormRequest
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
            'id' => ['integer', new FindRecord(new Article)],
            'comment_id' => ['integer'],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge(['id' => $this->route('id')]);
        $this->merge(['comment_id' => $this->route('comment_id')]);
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response(['message' => $validator->errors()->get('id')[0]],
                Response::HTTP_NOT_FOUND),
        );
    }

}
