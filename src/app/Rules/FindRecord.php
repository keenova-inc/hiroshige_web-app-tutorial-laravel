<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Model;

class FindRecord implements ValidationRule
{
    private $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $record = $this->model::find((int)$value);
        if(is_null($record)) {
            $fail(__('validation.exists', ['attribute' => __('validation.attributes.article')]));
        }
    }

}
