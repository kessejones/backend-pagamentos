<?php

namespace App\Services;

use App\Common\Result;
use Exception;

abstract class AbstractService
{
    protected function result(array $payload = [])
    {
        if($payload['status'])
            return Result::create_success($payload['data']);
        return Result::create_error($payload['error']);
    }

    protected function result_success($data = [])
    {
        return Result::create_success($data);
    }

    protected function result_error(Exception $exception)
    {
        return Result::create_error($exception);
    }
}
