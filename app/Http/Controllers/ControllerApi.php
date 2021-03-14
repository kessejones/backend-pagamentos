<?php

namespace App\Http\Controllers;

use App\Common\Result;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use RuntimeException;

class ControllerApi extends Controller
{
    /**
     * Response result data or error
     *
     * @param Result $result
     * @param string $resource_class
     * @param int $http_status
     *
     * @return JsonResponse
     */
    protected function response(Result $result, string $resource_class, int $http_status = Response::HTTP_OK)
    {
        if(!$result->status())
        {
            $exception = $result->exception();
            if($exception instanceof RuntimeException)
            {
                return abort(response()->json([
                    'error' => $exception->getMessage()
                ], Response::HTTP_UNPROCESSABLE_ENTITY));
            }
            return abort(response()->json(['message' => 'Internal Error', 'error' => $exception->getMessage()], 500));
        }

        $data = $result->data();
        $resource = new $resource_class($data);
        return response()->json($resource, $http_status);
    }

    /**
     * Response model data
     *
     * @param Model $model
     * @param string $resource_class
     * @param int $http_status
     *
     * @return JsonResponse
     */
    protected function response_model(Model $model, string $resource_class, int $http_status = Response::HTTP_OK)
    {
        $resource = new $resource_class($model);
        return response()->json($resource, $http_status);
    }
}
