<?php declare(strict_types=1);

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\FindRecord;
use App\Models\Comment;
use Illuminate\Contracts\Validation\Validator;
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
            'id' => [],
            'comment_id' => [new FindRecord(new Comment, trans('validation.attributes.message'))],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge(['id' => $this->route('id')]);
        $this->merge(['comment_id' => $this->route('comment_id')]);
    }

    public function failedValidation(Validator $validator)
    {
        if($validator->errors()->has('comment_id')) {
            abort(Response::HTTP_NOT_FOUND,
            $validator->errors()->get('comment_id')[0]);
        }
    }

}
