<?php declare(strict_types=1);

namespace App\Rules\Article;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ArticleTitle implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $maxCount = 255;
        if (!is_string($value) || mb_strlen($value) > $maxCount) {
            $fail(__('validation.max.string',
            ['attribute' => __('validation.attributes.title'), 'max' => $maxCount]));
        }
    }
}
