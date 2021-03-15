<?php

namespace App\Services;

use App\Events\TransferReceived;
use App\Jobs\SendTransferNotification;
use App\Models\Transaction;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class TransactionService extends AbstractService
{
    private $transaction_external_service;

    public function __construct(TransactionExternalService $transaction_external_service)
    {
        $this->transaction_external_service = $transaction_external_service;
    }

    public function create(array $data)
    {
        $result = [];

        try
        {
            DB::beginTransaction();
            if(floatval($data['value']) <= 0.01)
                throw new RuntimeException("Invalid amount for a transfer");

            if($data['payee'] == $data['payer'])
                throw new RuntimeException("Payer and payee must be different");

            $payee = User::find($data['payee']);
            $payer = User::find($data['payer']);

            if($payer->type->type_name == 'lojista')
                throw new RuntimeException("This user cannot create a transfer");

            if($payer->balance < $data['value'])
                throw new RuntimeException("This user has no balance");

            $transaction = Transaction::create([
                'payer_id' => $data['payer'],
                'payee_id' => $data['payee'],
                'value' => $data['value'],
            ]);

            $payer->balance -= $data['value'];
            $payer->save();

            $payee->balance += $data['value'];
            $payee->save();

            if(!$this->transaction_external_service->authorize())
                throw new RuntimeException("Unauthorized transaction");

            $result = [
                'status' => true,
                'data' => $transaction
            ];

            SendTransferNotification::dispatch($payee);

            DB::commit();
        }
        catch(Exception $e)
        {
            DB::rollback();
            $result = [
                'status' => false,
                'error' => $e
            ];
        }

        return $this->result($result);
    }
}
