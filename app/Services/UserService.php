<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class UserService extends AbstractService
{
    public function create(array $data)
    {
        $result = [];

        try
        {
            DB::beginTransaction();

            $user = User::create($data);
            $result = [
                'status' => true,
                'data' => $user
            ];

            DB::commit();
        }
        catch(Exception $e)
        {
            DB::rollback();
            $result = [
                'status' => false,
                'error' => $e
            ];
        }

        return $this->result($result);
    }

    public function add_balance(User $user, array $data)
    {
        $result = [];

        try
        {
            DB::beginTransaction();

            $user->balance += $data['value'];
            $user->save();

            $result = [
                'status' => true,
                'data' => $user
            ];

            DB::commit();
        }
        catch(Exception $e)
        {
            DB::rollback();
            $result = [
                'status' => false,
                'error' => $e
            ];
        }

        return $this->result($result);
    }
}
