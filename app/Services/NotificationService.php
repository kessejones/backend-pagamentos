<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;

class NotificationService extends AbstractService
{
    public function notify_payee(User $payee)
    {
        $response = Http::get('https://run.mocky.io/v3/b19f7b9f-9cbf-4fc6-ad22-dc30601aec04');
        return ($response->status() == 200 && $response->json('message') == 'Enviado');
    }
}
