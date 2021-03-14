<?php

namespace Tests\Unit;

use App\Models\UserType;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    private $user_service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user_service = $this->app->make('App\Services\UserService');
    }

    public function test_create_user_normal()
    {
        $type_normal = UserType::create([
            'type_name' => 'normal'
        ]);
        $this->assertNotNull($type_normal);

        $result = $this->user_service->create([
            'name' => 'user 1',
            'email' => 'user1@email.com',
            'document' => '24722016054',
            'type_id' => $type_normal->id
        ]);

        $this->assertTrue($result->status());
        $this->assertNull($result->exception());
    }

    public function test_create_user_normal_error_cnpj()
    {
        $type_normal = UserType::create([
            'type_name' => 'normal'
        ]);
        $this->assertNotNull($type_normal);

        $result = $this->user_service->create([
            'name' => 'user 1',
            'email' => 'user1@email.com',
            'document' => '61059679000189',
            'type_id' => $type_normal->id
        ]);

        $this->assertFalse($result->status());
        $this->assertNotNull($result->exception());
    }

    public function test_create_user_lojista_error_cpf()
    {
        $type_lojista = UserType::create([
            'type_name' => 'lojista'
        ]);
        $this->assertNotNull($type_lojista);

        $result = $this->user_service->create([
            'name' => 'user 1',
            'email' => 'user1@email.com',
            'document' => '00671318080',
            'type_id' => $type_lojista->id
        ]);

        $this->assertFalse($result->status());
        $this->assertNotNull($result->exception());
    }

    public function test_create_user_lojista()
    {
        $type_lojista = UserType::create([
            'type_name' => 'lojista'
        ]);
        $this->assertNotNull($type_lojista);

        $result = $this->user_service->create([
            'name' => 'user 1',
            'email' => 'user1@email.com',
            'document' => '65826676000184',
            'type_id' => $type_lojista->id
        ]);

        $this->assertTrue($result->status());
        $this->assertNull($result->exception());
    }

    public function test_create_user_normal_error_duplicate_email()
    {
        $type_normal = UserType::create([
            'type_name' => 'normal'
        ]);
        $this->assertNotNull($type_normal);

        $result_first = $this->user_service->create([
            'name' => 'user 1',
            'email' => 'user1@email.com',
            'document' => '47635632035',
            'type_id' => $type_normal->id
        ]);
        $this->assertTrue($result_first->status());

        $result_duplicate = $this->user_service->create([
            'name' => 'user 2',
            'email' => 'user1@email.com',
            'document' => '73451564009',
            'type_id' => $type_normal->id
        ]);

        $this->assertFalse($result_duplicate->status());
        $this->assertNotNull($result_duplicate->exception());
    }

    public function test_create_user_normal_error_duplicate_document()
    {
        $type_normal = UserType::create([
            'type_name' => 'normal'
        ]);
        $this->assertNotNull($type_normal);

        $result_first = $this->user_service->create([
            'name' => 'user 1',
            'email' => 'user1@email.com',
            'document' => '47635632035',
            'type_id' => $type_normal->id
        ]);
        $this->assertTrue($result_first->status());

        $result_duplicate = $this->user_service->create([
            'name' => 'user 2',
            'email' => 'user2@email.com',
            'document' => '47635632035',
            'type_id' => $type_normal->id
        ]);

        $this->assertFalse($result_duplicate->status());
        $this->assertNotNull($result_duplicate->exception());
    }

    public function test_create_user_lojista_error_duplicate_email()
    {
        $type_lojista = UserType::create([
            'type_name' => 'lojista'
        ]);
        $this->assertNotNull($type_lojista);

        $result_first = $this->user_service->create([
            'name' => 'user 1',
            'email' => 'user1@email.com',
            'document' => '65826676000184',
            'type_id' => $type_lojista->id
        ]);
        $this->assertTrue($result_first->status());

        $result_duplicate = $this->user_service->create([
            'name' => 'user 2',
            'email' => 'user1@email.com',
            'document' => '10885269000156',
            'type_id' => $type_lojista->id
        ]);

        $this->assertFalse($result_duplicate->status());
        $this->assertNotNull($result_duplicate->exception());
    }

    public function test_create_user_lojista_error_duplicate_document()
    {
        $type_lojista = UserType::create([
            'type_name' => 'lojista'
        ]);
        $this->assertNotNull($type_lojista);

        $result_first = $this->user_service->create([
            'name' => 'user 1',
            'email' => 'user1@email.com',
            'document' => '65826676000184',
            'type_id' => $type_lojista->id
        ]);
        $this->assertTrue($result_first->status());

        $result_duplicate = $this->user_service->create([
            'name' => 'user 2',
            'email' => 'user2@email.com',
            'document' => '65826676000184',
            'type_id' => $type_lojista->id
        ]);

        $this->assertFalse($result_duplicate->status());
        $this->assertNotNull($result_duplicate->exception());
    }
}
