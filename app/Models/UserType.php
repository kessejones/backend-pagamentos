<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * class UserType
 *
 * @property int $id
 * @property string $type_name
 * -
 * @property Collection|User[] $users
 */
class UserType extends Model
{
    use HasFactory;

    protected $table = 'users_types';

    protected $fillable = [
        'type_name'
    ];

    public $timestamps = false;

    public function users()
    {
        return $this->hasMany(User::class, 'type_id', 'id');
    }
}
