<?php declare(strict_types=1);

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\FindRecord;
use App\Models\Article;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use App\Rules\Page;
use Illuminate\Contracts\Validation\Validator;


class SearchCommentRequest extends FormRequest
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
            'page' => [new Page],
            'id' => ['integer', new FindRecord(new Article)],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge(['id' => $this->route('id')]);
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response(['message' => $validator->errors()],
                Response::HTTP_BAD_REQUEST),
        );
    }


}
