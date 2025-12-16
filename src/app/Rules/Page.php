<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Page implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        if (!preg_match('/^[1-9][0-9]*$/u', $value)) {
            $fail(__('validation.invalid', ['attribute' => __('validation.attributes.page')]));
        }
    }
}
