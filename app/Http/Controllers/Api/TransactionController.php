<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ControllerApi;
use App\Http\Resources\TransactionResource;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TransactionController extends ControllerApi
{
    private $transaction_service;

    public function __construct(TransactionService $transaction_service)
    {
        $this->transaction_service = $transaction_service;
    }

    public function create(Request $request)
    {
        $data_valid = $request->validate([
            'value' => 'required|numeric|min:0.01',
            'payer' => 'required|exists:users,id',
            'payee' => 'required|exists:users,id',
        ]);

        $result = $this->transaction_service->create($data_valid);
        return $this->response($result, TransactionResource::class, Response::HTTP_CREATED);
    }
}
