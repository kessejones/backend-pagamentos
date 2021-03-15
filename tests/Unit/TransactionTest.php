<?php

namespace Tests\Unit;

use App\Models\Transaction;
use App\Models\User;
use App\Models\UserType;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_create_transaction()
    {
        $type_normal = UserType::create([
            'type_name' => 'normal'
        ]);

        $this->assertDatabaseHas('users_types', [
            'type_name' => 'normal',
        ]);

        $type_lojista = UserType::create([
            'type_name' => 'lojista'
        ]);

        $this->assertDatabaseHas('users_types', [
            'type_name' => 'lojista',
        ]);

        $user_payer = User::factory()->make([
            'type_id' => $type_normal->id,
            'balance' => 100
        ]);
        $user_payer->save();

        $this->assertDatabaseHas('users', [
            'email' => $user_payer->email,
            'document' => $user_payer->document
        ]);

        $user_payee = User::factory()->make([
            'type_id' => $type_lojista->id,
            'balance' => 0
        ]);
        $user_payee->save();

        $this->assertDatabaseHas('users', [
            'email' => $user_payee->email,
            'document' => $user_payee->document
        ]);

        $transaction = Transaction::create([
            'payer_id' => $user_payer->id,
            'payee_id' => $user_payee->id,
            'value' => 10
        ]);

        $this->assertNotNull($transaction);
        $this->assertDatabaseHas('transactions', [
            'payer_id' => $user_payer->id,
            'payee_id' => $user_payee->id,
            'value' => 10
        ]);
    }
}
