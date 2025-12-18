<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Actions\Fortify\CreateNewUser;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    private $userSvc;

    public function __construct(
        CreateNewUser $createNewUser
    )
    {
        $this->userSvc = $createNewUser;
    }

    function create(Request $request): JsonResponse
    {
        $params = $request->all();

        $user = $this->userSvc->create($params);
        $attribute = __('validation.attributes.user');
        $message = $user ? __('api.create.success', ['id' => $user->id, 'attribute' => $attribute])
            : __('api.create.fail', ['attribute' => $attribute]);

        return response()->json(compact('user', 'message'));
    }
}
