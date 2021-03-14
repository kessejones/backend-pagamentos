<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users_types')->insert([
            ['id' => 1, 'type_name' => 'normal'],
            ['id' => 2, 'type_name' => 'lojista'],
        ]);
    }
}
