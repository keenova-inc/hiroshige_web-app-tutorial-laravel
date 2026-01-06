<?php declare(strict_types=1);

namespace App\Http\Requests\Article;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Page;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class SearchArticleRequest extends FormRequest
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
        ];
    }

    public function prepareForValidation()
    {
        $this->merge(['page' => $this->query('page', 1)]);
    }

    public function failedValidation(Validator $validator)
    {
        if($validator->errors()->has('page')) {
            throw new HttpResponseException(
                response(['message' => $validator->errors()->get('page')[0]],
                    Response::HTTP_BAD_REQUEST),
            );
        }
    }
}
