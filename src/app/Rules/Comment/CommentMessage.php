<?php

declare(strict_types=1);

namespace App\Rules\Comment;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CommentMessage implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $maxCount = 500;
        if (!is_string($value) || mb_strlen($value) > $maxCount) {
            $fail(__(
                'validation.max.string',
                ['attribute' => __('validation.attributes.message'), 'max' => $maxCount]
            ));
        }
    }
}
