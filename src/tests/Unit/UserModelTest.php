<?php declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('User Modelのインスタンス化時に機密情報が隠蔽されている', function() {
    $user = new User([
        'name' => '山田花子',
        'email' => 'hanako@example.com',
        'password' => Hash::make('password'),
    ]);

    expect($user)->toHaveKeys([
        'name',
        'email',
    ])
    ->not->toHaveKeys([
        'password',
        'created_at',
        'updated_at',
        'deleted_at',
    ]);
});
