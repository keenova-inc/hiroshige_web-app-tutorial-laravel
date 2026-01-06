<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;

class FindRecord implements ValidationRule
{
    private $model;
    private $name;

    public function __construct(Model $model, string $name)
    {
        $this->model = $model;
        $this->name = $name;
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
            abort(Response::HTTP_NOT_FOUND,
                trans('api.not_exist', ['id' => $value, 'attribute' => $this->name])
            );
        }
    }

}
