<?php

namespace App\Rules\Article;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ArticleUserName implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $maxCount = 50;
        if (!is_string($value) || mb_strlen($value) > $maxCount) {
            $fail(__('validation.max.string',
            ['attribute' => __('validation.attributes.username'), 'max' => $maxCount]));
        }
    }
}
