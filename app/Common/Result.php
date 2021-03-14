<?php

namespace App\Common;

use Exception;

class Result
{
    protected $data = [];
    protected $status = false;
    protected $exception;

    public function status()
    {
        return $this->status;
    }

    public function data()
    {
        return $this->data;
    }

    public function exception()
    {
        return $this->exception;
    }

    public static function create_success($data)
    {
        $instance = new self;
        $instance->data = $data;
        $instance->status = true;
        return $instance;
    }

    public static function create_error(Exception $exception)
    {
        $instance = new self;
        $instance->exception = $exception;
        $instance->status = false;
        return $instance;
    }
}
