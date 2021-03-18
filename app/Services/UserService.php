<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserType;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class UserService extends AbstractService
{
    private function validate_document(UserType $type, $document)
    {
        $str = Str::of($document)->replaceMatches('/(\D)/', '');
        if($type->type_name == 'lojista')
            return $str->length() == 14;
        if($type->type_name == 'normal')
            return $str->length() == 11;
        return false;
    }

    public function all()
    {
        $users = User::all();
        return $this->result_success($users);
    }

    public function create(array $data)
    {
        $result = [];

        try
        {
            DB::beginTransaction();

            $user_type = UserType::find($data['type_id']);
            if(!$this->validate_document($user_type, $data['document']))
                throw new RuntimeException("Invalid document for user type");

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

            if(floatval($data['value']) < 0.01)
                throw new RuntimeException("Invalid amount for a deposit");

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
