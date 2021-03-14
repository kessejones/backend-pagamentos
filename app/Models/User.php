<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

/**
 * class User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $document
 * @property float $balance
 * @property int $type_id
 * -
 * @property UserType $type
 * @property Collection|Transaction[] $payee_transactions
 * @property Collection|Transaction[] $payer_transactions
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'document',
        'password',
        'balance',
        'type_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    protected $casts = [
        'balance' => 'float'
    ];

    public $timestamps = false;

    public function type()
    {
        return $this->hasOne(UserType::class, 'id', 'type_id');
    }

    public function payee_transactions()
    {
        return $this->hasMany(Transaction::class, 'id', 'payee_id');
    }

    public function payer_transactions()
    {
        return $this->hasMany(Transaction::class, 'id', 'payer_id');
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function(User $user){
            $user->password = bcrypt($user->password);
        });
    }
}
