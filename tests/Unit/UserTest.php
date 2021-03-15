<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\UserType;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_user_normal()
    {
        $type_normal = UserType::create([
            'type_name' => 'normal'
        ]);
        $this->assertDatabaseHas('users_types', [
            'type_name' => 'normal',
        ]);

        $this->assertNotNull($type_normal);

        $user_normal = User::factory()->make([
            'type_id' => $type_normal->id
        ]);
        $user_normal->save();

        $this->assertDatabaseHas('users', [
            'document' => $user_normal->document,
        ]);

        $this->assertNotNull($user_normal);
        $this->assertEquals($user_normal->type->id, $type_normal->id);
    }

    public function test_create_user_lojista()
    {
        $type_lojista = UserType::create([
            'type_name' => 'lojista'
        ]);
        $this->assertDatabaseHas('users_types', [
            'type_name' => 'lojista',
        ]);

        $this->assertNotNull($type_lojista);

        $user_lojista = User::factory()->make([
            'type_id' => $type_lojista->id
        ]);
        $user_lojista->save();

        $this->assertDatabaseHas('users', [
            'document' => $user_lojista->document,
        ]);

        $this->assertNotNull($user_lojista);
        $this->assertEquals($user_lojista->type->id, $type_lojista->id);
    }
}
