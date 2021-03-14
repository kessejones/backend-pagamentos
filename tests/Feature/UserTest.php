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
}
