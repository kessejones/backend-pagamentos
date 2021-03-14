<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactonTest extends TestCase
{
    use RefreshDatabase;

    private $user_type_normal;
    private $user_normal;
    private $user_type_lojista;
    private $user_lojista;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user_type_normal = UserType::create([
            'type_name' => 'normal'
        ]);

        $this->user_type_lojista = UserType::create([
            'type_name' => 'lojista'
        ]);

        $this->user_normal = User::factory()->make([
            'document' => '51979039020',
            'type_id' => $this->user_type_normal->id,
            'balance' => 100
        ]);
        $this->user_normal->save();

        $this->user_lojista = User::factory()->make([
            'document' => '72770423010',
            'type_id' => $this->user_type_lojista->id
        ]);
        $this->user_lojista->save();
    }

    public function test_create_transaction()
    {
        $this->assertNotNull($this->user_lojista);
        $this->assertNotNull($this->user_normal);

        $response = $this->postJson('/api/transaction', [
            'value' => 10,
            'payee' => $this->user_lojista->id,
            'payer' => $this->user_normal->id,
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id', 'value', 'payer_id', 'payee_id'
        ]);
    }

    public function test_create_transaction_error_zero_balance()
    {
        $this->assertNotNull($this->user_lojista);
        $user_normal = User::factory()->make([
            'document' => '51979039025',
            'type_id' => $this->user_type_normal->id,
            'balance' => 0
        ]);

        $this->assertNotNull($user_normal);

        $response = $this->postJson('/api/transaction', [
            'value' => 10,
            'payee' => $this->user_lojista->id,
            'payer' => $user_normal->id,
        ]);

        $response->assertStatus(422);
    }

    public function test_create_transaction_error_lojista()
    {
        $this->assertNotNull($this->user_lojista);
        $this->assertNotNull($this->user_normal);

        $response = $this->postJson('/api/transaction', [
            'value' => 10,
            'payer' => $this->user_lojista->id,
            'payee' => $this->user_normal->id,
        ]);

        $response->assertStatus(422);
    }
}
