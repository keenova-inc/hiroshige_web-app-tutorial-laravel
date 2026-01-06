<?php declare(strict_types=1);

namespace App\Util;

use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class HandleException {

    /**
     * 例外発生時に返却するHTTPステータスを決定
     * @param Exception $e
     * @return int
     */
    public static function decideStatus(Exception $e): int {
        $status = Response::HTTP_INTERNAL_SERVER_ERROR;
        if($e instanceof ModelNotFoundException) {
            $status = Response::HTTP_NOT_FOUND;
        }
        return $status;
    }

}
