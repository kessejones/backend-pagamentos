<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ControllerApi;
use App\Http\Resources\UserResource;
use App\Http\Resources\UsersResource;
use App\Models\User;
use App\Rules\DocumentSizeRule;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Http\Response;

class UserController extends ControllerApi
{
    private $user_service;

    public function __construct(UserService $user_service)
    {
        $this->user_service = $user_service;
    }

    public function all()
    {
        $result = $this->user_service->all();
        return $this->response($result, UsersResource::class);
    }

    public function data(User $user)
    {
        return $this->response_model($user, UserResource::class);
    }

    public function create(Request $request)
    {
        $data_valid = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'document' => ['required', 'string', 'unique:users,document', new DocumentSizeRule],
            'type_id' => 'required|exists:users_types,id'
        ]);

        $result = $this->user_service->create($data_valid);
        return $this->response($result, UserResource::class, Response::HTTP_CREATED);
    }

    public function add_balance(User $user, Request $request)
    {
        $data_valid = $request->validate([
            'value' => 'required|numeric|min:0.01',
        ]);

        $result = $this->user_service->add_balance($user, $data_valid);
        return $this->response($result, UserResource::class, Response::HTTP_CREATED);
    }
}
