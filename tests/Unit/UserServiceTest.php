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

    public function test_create_user_lojista_error_document_invalid()
    {
        $type_lojista = UserType::create([
            'type_name' => 'lojista'
        ]);
        $this->assertNotNull($type_lojista);

        $result = $this->user_service->create([
            'name' => 'user 1',
            'email' => 'user1@email.com',
            'document' => '1',
            'type_id' => $type_lojista->id
        ]);

        $this->assertFalse($result->status());
        $this->assertNotNull($result->exception());
    }

    public function test_create_user_lojista_error_lojista_cpf()
    {
        $type_lojista = UserType::create([
            'type_name' => 'lojista'
        ]);
        $this->assertNotNull($type_lojista);

        $result = $this->user_service->create([
            'name' => 'user 1',
            'email' => 'user1@email.com',
            'document' => '48632491016',
            'type_id' => $type_lojista->id
        ]);

        $this->assertFalse($result->status());
        $this->assertNotNull($result->exception());
    }

    public function test_create_user_lojista_error_normal_cnpj()
    {
        $type_normal = UserType::create([
            'type_name' => 'normal'
        ]);
        $this->assertNotNull($type_normal);

        $result = $this->user_service->create([
            'name' => 'user 1',
            'email' => 'user1@email.com',
            'document' => '60833115000199',
            'type_id' => $type_normal->id
        ]);

        $this->assertFalse($result->status());
        $this->assertNotNull($result->exception());
    }

    public function test_create_deposit_success()
    {
        $type_normal = UserType::create([
            'type_name' => 'normal'
        ]);
        $this->assertNotNull($type_normal);

        $result_user = $this->user_service->create([
            'name' => 'user 1',
            'email' => 'user1@email.com',
            'document' => '24722016054',
            'type_id' => $type_normal->id
        ]);

        $this->assertTrue($result_user->status());
        $this->assertNull($result_user->exception());
        $this->assertNotNull($result_user->data());

        $user = $result_user->data();
        $result_deposit = $this->user_service->add_balance($user, [
            'value' => 10,
        ]);

        $this->assertTrue($result_deposit->status());
        $this->assertNull($result_deposit->exception());

        $user->refresh();
        $this->assertEquals($user->balance, 10);
    }

    public function test_create_deposit_value_invalid()
    {
        $type_normal = UserType::create([
            'type_name' => 'normal'
        ]);
        $this->assertNotNull($type_normal);

        $result_user = $this->user_service->create([
            'name' => 'user 1',
            'email' => 'user1@email.com',
            'document' => '24722016054',
            'type_id' => $type_normal->id
        ]);

        $this->assertTrue($result_user->status());
        $this->assertNull($result_user->exception());
        $this->assertNotNull($result_user->data());

        $user = $result_user->data();
        $result_deposit = $this->user_service->add_balance($user, [
            'value' => 0,
        ]);

        $this->assertFalse($result_deposit->status());
        $this->assertNotNull($result_deposit->exception());
    }
}
