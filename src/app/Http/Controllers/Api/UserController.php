<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Actions\Fortify\CreateNewUser;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Http\Requests\User\CreateUserRequest;

class UserController extends Controller
{
    private $userSvc;

    public function __construct(
        CreateNewUser $createNewUser
    ) {
        $this->userSvc = $createNewUser;
    }

    /**
     * ユーザ登録
     * @param CreateUserRequest $request
     * @return JsonResponse
     */
    public function create(CreateUserRequest $request): JsonResponse
    {
        $params = $request->all();

        try {
            $user = $this->userSvc->create($params);
        } catch (Exception $e) {
            $user = null;
        }

        $attribute = __('validation.attributes.user');
        $message = $user ? __('api.create.success', ['id' => $user->id, 'attribute' => $attribute])
            : __('api.create.fail', ['attribute' => $attribute]);
        $status = $user ? Response::HTTP_CREATED : Response::HTTP_INTERNAL_SERVER_ERROR;
        $resArray = is_null($user) ? compact('message') : compact('user', 'message');

        return response()->json($resArray, $status);
    }
}
