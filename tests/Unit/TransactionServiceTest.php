<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\UserType;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionServiceTest extends TestCase
{
    use RefreshDatabase;

    private $transaction_service;
    private $user_type_normal;
    private $user_type_lojista;

    protected function setUp(): void
    {
        parent::setUp();

        $this->transaction_service = $this->app->make('App\Services\TransactionService');

        $this->user_type_normal = UserType::create([
            'type_name' => 'normal'
        ]);

        $this->user_type_lojista = UserType::create([
            'type_name' => 'lojista'
        ]);
    }

    private function make_user(UserType $type, float $balance = 0)
    {
        $user = User::factory()->make([
            'type_id' => $type->id,
            'balance' => $balance
        ]);

        $user->save();

        return $user;
    }

    public function test_create_transaction()
    {
        $user_normal = $this->make_user($this->user_type_normal, 100);
        $this->assertNotNull($user_normal);

        $user_lojista = $this->make_user($this->user_type_lojista, 0);
        $this->assertNotNull($user_lojista);

        $result = $this->transaction_service->create([
            'payer' => $user_normal->id,
            'payee' => $user_lojista->id,
            'value' => 10
        ]);

        $this->assertTrue($result->status());

        $user_normal->refresh();
        $user_lojista->refresh();

        $this->assertEquals($user_normal->balance, 90);
        $this->assertEquals($user_lojista->balance, 10);
    }

    public function test_create_transaction_normal_users()
    {
        $user_payer = $this->make_user($this->user_type_normal, 109);
        $this->assertNotNull($user_payer);

        $user_payee = $this->make_user($this->user_type_normal, 7);
        $this->assertNotNull($user_payee);

        $result = $this->transaction_service->create([
            'payer' => $user_payer->id,
            'payee' => $user_payee->id,
            'value' => 10
        ]);

        $this->assertTrue($result->status());

        $user_payer->refresh();
        $user_payee->refresh();

        $this->assertEquals($user_payer->balance, 99);
        $this->assertEquals($user_payee->balance, 17);
    }

    public function test_create_transaction_error_no_balance_normal_users()
    {
        $user_payer = $this->make_user($this->user_type_normal, 109);
        $this->assertNotNull($user_payer);

        $user_payee = $this->make_user($this->user_type_normal, 7);
        $this->assertNotNull($user_payee);

        $result = $this->transaction_service->create([
            'payer' => $user_payer->id,
            'payee' => $user_payee->id,
            'value' => 110
        ]);

        $this->assertFalse($result->status());
    }

    public function test_create_transaction_error_equal_users()
    {
        $user_normal = $this->make_user($this->user_type_normal, 100);
        $this->assertNotNull($user_normal);

        $result = $this->transaction_service->create([
            'payer' => $user_normal->id,
            'payee' => $user_normal->id,
            'value' => 10
        ]);

        $this->assertFalse($result->status());
    }

    public function test_create_transaction_error_no_balance()
    {
        $user_normal = $this->make_user($this->user_type_normal, 10);
        $this->assertNotNull($user_normal);

        $user_lojista = $this->make_user($this->user_type_lojista, 0);
        $this->assertNotNull($user_lojista);

        $result = $this->transaction_service->create([
            'payer' => $user_normal->id,
            'payee' => $user_lojista->id,
            'value' => 11
        ]);

        $this->assertFalse($result->status());
    }

    public function test_create_transaction_error_lojista()
    {
        $user_normal = $this->make_user($this->user_type_normal, 100);
        $this->assertNotNull($user_normal);

        $user_lojista = $this->make_user($this->user_type_lojista, 100);
        $this->assertNotNull($user_lojista);

        $result = $this->transaction_service->create([
            'payer' => $user_lojista->id,
            'payee' => $user_normal->id,
            'value' => 10
        ]);

        $this->assertFalse($result->status());
    }

    public function test_create_transaction_error_invalid_value()
    {
        $user_normal = $this->make_user($this->user_type_normal, 100);
        $this->assertNotNull($user_normal);

        $user_lojista = $this->make_user($this->user_type_lojista, 100);
        $this->assertNotNull($user_lojista);

        $result = $this->transaction_service->create([
            'payer' => $user_lojista->id,
            'payee' => $user_normal->id,
            'value' => -10
        ]);

        $this->assertFalse($result->status());
    }
}
