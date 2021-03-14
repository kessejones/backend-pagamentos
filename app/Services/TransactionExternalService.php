<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TransactionExternalService extends AbstractService
{
    public function authorize()
    {
        $response = Http::get('https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6');
        return ($response->status() == 200 && $response->json('message') == 'Autorizado');
    }
}
