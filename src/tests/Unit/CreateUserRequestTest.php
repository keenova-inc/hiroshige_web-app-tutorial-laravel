<?php

declare(strict_types=1);

use App\Http\Requests\User\CreateUserRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Unique;

it('名前が101文字以上であればバリデーションエラーになる', function ($name, $failFlag) {
    $formRequest = new CreateUserRequest();
    $requestRules = $formRequest->rules();

    $filteredEmailRules = array_filter($requestRules['email'], function ($emailRule) {
        return !($emailRule instanceof Unique); // DBに接続させないためユニーク制約は削除
    });
    $requestRules['email'] = $filteredEmailRules;

    $request = [
        'name' => $name,
        'email' => 'hanako@exmaple.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ];

    $validator = Validator::make($request, $requestRules);
    expect($validator->fails())->toBe($failFlag);

    // バリデーション失敗時の挙動
    if ($failFlag === true) {
        expect($validator->errors()->has('name'))->toBeTrue
            ->and($validator->errors()->messages()['name'][0])
            ->toBe(trans(
                'validation.max.string',
                [
                    'attribute' => trans('validation.attributes.name'),
                    'max' => 100
                ],
            ));
    }

})->with([
    '101文字のためエラー' => [Str::random(101), true],
    '100文字のため正常' => [Str::random(100), false],
]);
