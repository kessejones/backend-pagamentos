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

    public function test_create_transaction_normal_users()
    {
        $this->assertNotNull($this->user_normal);

        $user_normal_payee = User::factory()->make([
            'document' => '65813909095',
            'type_id' => $this->user_type_normal->id,
            'balance' => 100
        ]);

        $this->assertNotNull($user_normal_payee);
        $user_normal_payee->save();

        $this->assertDatabaseHas('users', [
            'document' => '65813909095',
            'balance' => 100
        ]);

        $response = $this->postJson('/api/transaction', [
            'value' => 10,
            'payee' => $user_normal_payee->id,
            'payer' => $this->user_normal->id,
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id', 'value', 'payer_id', 'payee_id'
        ]);
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
        $response->assertJsonStructure(['message']);
    }

    public function test_create_transaction_error_payer_lojista()
    {
        $this->assertNotNull($this->user_lojista);
        $this->assertNotNull($this->user_normal);

        $response = $this->postJson('/api/transaction', [
            'value' => 10,
            'payer' => $this->user_lojista->id,
            'payee' => $this->user_normal->id,
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message']);
    }

    public function test_create_transaction_error_zero_value()
    {
        $this->assertNotNull($this->user_lojista);
        $this->assertNotNull($this->user_normal);

        $response = $this->postJson('/api/transaction', [
            'value' => 0,
            'payer' => $this->user_normal->id,
            'payee' => $this->user_lojista->id,
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors']);
        $response->assertJsonValidationErrors([
            'value'
        ]);
    }

    public function test_create_transaction_error_negative_value()
    {
        $this->assertNotNull($this->user_lojista);
        $this->assertNotNull($this->user_normal);

        $response = $this->postJson('/api/transaction', [
            'value' => -90,
            'payer' => $this->user_normal->id,
            'payee' => $this->user_lojista->id,
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors']);
        $response->assertJsonValidationErrors([
            'value'
        ]);
    }

    public function test_create_transaction_error_invalid_payer()
    {
        $this->assertNotNull($this->user_lojista);

        $response = $this->postJson('/api/transaction', [
            'value' => 10,
            'payer' => 90,
            'payee' => $this->user_lojista->id,
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors']);
        $response->assertJsonValidationErrors([
            'payer'
        ]);
    }

    public function test_create_transaction_error_invalid_payee()
    {
        $this->assertNotNull($this->user_normal);

        $response = $this->postJson('/api/transaction', [
            'value' => 10,
            'payer' => $this->user_normal->id,
            'payee' => 88,
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors']);
        $response->assertJsonValidationErrors([
            'payee'
        ]);
    }

    public function test_create_transaction_error_invalid_payer_and_payee()
    {
        $response = $this->postJson('/api/transaction', [
            'value' => 10,
            'payer' => 90,
            'payee' => 187,
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors']);
        $response->assertJsonValidationErrors([
            'payer',
            'payee',
        ]);
    }
}
