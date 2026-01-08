<?php

declare(strict_types=1);

namespace App\Http\Requests\Article;

use App\Models\Article;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\FindRecord;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Contracts\Validation\Validator;

class FindArticleRequest extends FormRequest
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
            'id' => [new FindRecord(new Article(), trans('validation.attributes.article'))],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge(['id' => $this->route('id')]);
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response(
                ['message' => $validator->errors()->get('id')[0]],
                Response::HTTP_NOT_FOUND
            ),
        );
    }

}
