<?php declare(strict_types=1);

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Comment\CommentMessage;
use App\Rules\FindRecord;
use App\Models\Article;
use App\Models\Comment;

class UpdateCommentRequest extends FormRequest
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
            'id' => ['integer', new FindRecord(new Article)],
            'comment_id' => ['integer', new FindRecord(new Comment)],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge(['id' => $this->route('id')]);
        $this->merge(['comment_id' => $this->route('comment_id')]);
    }

}
