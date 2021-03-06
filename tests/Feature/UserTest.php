<?php

namespace Tests\Feature;

use App\Models\UserType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private $user_type_normal;
    private $user_type_lojista;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user_type_normal = UserType::create([
            'type_name' => 'normal'
        ]);

        $this->user_type_lojista = UserType::create([
            'type_name' => 'lojista'
        ]);
    }

    public function test_create_user_normal()
    {
        $this->assertNotNull($this->user_type_normal);

        $response = $this->postJson('/api/user', [
            'name' => 'user 1',
            'email' => 'user1@system.com',
            'document' => '25141104087',
            'password' => '123123',
            'type_id' => $this->user_type_normal->id
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id', 'name', 'email', 'document'
        ]);
    }

    public function test_users_list()
    {
        $this->assertNotNull($this->user_type_normal);

        $response1 = $this->postJson('/api/user', [
            'name' => 'user 1',
            'email' => 'user1@system.com',
            'document' => '25141104087',
            'password' => '123123',
            'type_id' => $this->user_type_normal->id
        ]);

        $response1->assertStatus(201);
        $response1->assertJsonStructure([
            'id', 'name', 'email', 'document'
        ]);

        $response2 = $this->postJson('/api/user', [
            'name' => 'user 2',
            'email' => 'user2@system.com',
            'document' => '56817851220',
            'password' => '312312',
            'type_id' => $this->user_type_normal->id
        ]);

        $response2->assertStatus(201);
        $response2->assertJsonStructure([
            'id', 'name', 'email', 'document'
        ]);

        $response = $this->getJson('/api/user');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id', 'name', 'email', 'balance', 'document', 'type_id'
            ]
        ]);
    }

    public function test_deposit_success()
    {
        $this->assertNotNull($this->user_type_normal);

        $response_user = $this->postJson('/api/user', [
            'name' => 'user 1',
            'email' => 'user1@system.com',
            'document' => '25141104087',
            'password' => '123123',
            'type_id' => $this->user_type_normal->id
        ]);

        $response_user->assertStatus(201);
        $response_user->assertJsonStructure([
            'id', 'name', 'email', 'document'
        ]);

        $id_user = $response_user->json('id');

        $response_deposit = $this->postJson("/api/user/{$id_user}/deposit", [
            'value' => 134
        ]);

        $response_deposit->assertStatus(201);
        $response_deposit->assertJsonStructure([
            'balance'
        ]);
    }

    public function test_deposit_invalid_value()
    {
        $this->assertNotNull($this->user_type_normal);

        $response_user = $this->postJson('/api/user', [
            'name' => 'user 1',
            'email' => 'user1@system.com',
            'document' => '25141104087',
            'password' => '123123',
            'type_id' => $this->user_type_normal->id
        ]);

        $response_user->assertStatus(201);
        $response_user->assertJsonStructure([
            'id', 'name', 'email', 'document'
        ]);

        $id_user = $response_user->json('id');

        $response_deposit = $this->postJson("/api/user/{$id_user}/deposit", [
            'value' => 0
        ]);

        $response_deposit->assertStatus(422);
        $response_deposit->assertJsonStructure([
            'message', 'errors'
        ]);
    }

    public function test_create_user_lojista()
    {
        $this->assertNotNull($this->user_type_lojista);

        $response = $this->postJson('/api/user', [
            'name' => 'user 1',
            'email' => 'user1@system.com',
            'document' => '59825165000145',
            'password' => '323123',
            'type_id' => $this->user_type_lojista->id
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id', 'name', 'email', 'document'
        ]);
    }

    public function test_create_user_normal_error_document()
    {
        $this->assertNotNull($this->user_type_normal);

        $response = $this->postJson('/api/user', [
            'name' => 'user 1',
            'email' => 'user1@system.com',
            'document' => '1',
            'password' => '123123',
            'type_id' => $this->user_type_normal->id
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message', 'errors'
        ]);
        $response->assertJsonValidationErrors([
            'document'
        ]);
    }

    public function test_create_user_lojista_error_document()
    {
        $this->assertNotNull($this->user_type_lojista);

        $response = $this->postJson('/api/user', [
            'name' => 'user 1',
            'email' => 'user1@system.com',
            'document' => '5982516',
            'password' => '323123',
            'type_id' => $this->user_type_lojista->id
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message', 'errors'
        ]);
        $response->assertJsonValidationErrors([
            'document'
        ]);
    }

    public function test_create_user_normal_error_duplicate_document()
    {
        $this->assertNotNull($this->user_type_normal);

        $response = $this->postJson('/api/user', [
            'name' => 'user 1',
            'email' => 'user1@system.com',
            'document' => '25141104087',
            'password' => '123123',
            'type_id' => $this->user_type_normal->id
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id', 'name', 'email', 'document'
        ]);

        $response_error = $this->postJson('/api/user', [
            'name' => 'user 2',
            'email' => 'user2@system.com',
            'document' => '25141104087',
            'password' => 'password123',
            'type_id' => $this->user_type_normal->id
        ]);

        $response_error->assertStatus(422);
        $response_error->assertJsonStructure([
            'message', 'errors'
        ]);
        $response_error->assertJsonValidationErrors([
            'document'
        ]);
    }

    public function test_create_user_normal_error_duplicate_email()
    {
        $this->assertNotNull($this->user_type_normal);

        $response = $this->postJson('/api/user', [
            'name' => 'user 1',
            'email' => 'user1@system.com',
            'document' => '25141104087',
            'password' => '123123',
            'type_id' => $this->user_type_normal->id
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id', 'name', 'email', 'document'
        ]);

        $response_error = $this->postJson('/api/user', [
            'name' => 'user 2',
            'email' => 'user1@system.com',
            'document' => '41530225000',
            'password' => 'password123',
            'type_id' => $this->user_type_normal->id
        ]);

        $response_error->assertStatus(422);
        $response_error->assertJsonStructure([
            'message', 'errors'
        ]);
        $response_error->assertJsonValidationErrors([
            'email'
        ]);
    }

    public function test_create_user_lojista_error_duplicate_document()
    {
        $this->assertNotNull($this->user_type_normal);

        $response = $this->postJson('/api/user', [
            'name' => 'user 1',
            'email' => 'user1@system.com',
            'document' => '59825165000145',
            'password' => '323123',
            'type_id' => $this->user_type_lojista->id
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id', 'name', 'email', 'document'
        ]);

        $response_error = $this->postJson('/api/user', [
            'name' => 'user 2',
            'email' => 'user2@system.com',
            'document' => '59825165000145',
            'password' => 'password123',
            'type_id' => $this->user_type_lojista->id
        ]);

        $response_error->assertStatus(422);
        $response_error->assertJsonStructure([
            'message', 'errors'
        ]);
        $response_error->assertJsonValidationErrors([
            'document'
        ]);
    }

    public function test_create_user_lojista_error_duplicate_email()
    {
        $this->assertNotNull($this->user_type_normal);

        $response = $this->postJson('/api/user', [
            'name' => 'user 1',
            'email' => 'user1@system.com',
            'document' => '59825165000145',
            'password' => '323123',
            'type_id' => $this->user_type_lojista->id
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id', 'name', 'email', 'document'
        ]);

        $response_error = $this->postJson('/api/user', [
            'name' => 'user 2',
            'email' => 'user1@system.com',
            'document' => '53899051000163',
            'password' => 'password123',
            'type_id' => $this->user_type_lojista->id
        ]);

        $response_error->assertStatus(422);
        $response_error->assertJsonStructure([
            'message', 'errors'
        ]);
        $response_error->assertJsonValidationErrors([
            'email'
        ]);
    }
}
