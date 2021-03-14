<?php

namespace Tests\Unit;

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

        $this->assertTrue(!is_null($type_normal));

        $type_lojista = UserType::create([
            'type_name' => 'lojista'
        ]);

        $this->assertTrue(!is_null($type_lojista));

        $user_payer = $type_normal->users()->create([
            'name' => 'user 1',
            'email' => 'user1@system.com',
            'document' => '12112343112',
            'password' => bcrypt('123456')
        ]);

        $this->assertTrue(!is_null($user_payer));

        $user_payee = $type_lojista->users()->create([
            'name' => 'user 1',
            'email' => 'user2@system.com',
            'document' => '12112343113',
            'password' => bcrypt('123456')
        ]);

        $this->assertTrue(!is_null($user_payee));
    }
}
