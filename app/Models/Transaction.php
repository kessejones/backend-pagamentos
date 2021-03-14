<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * class Transaction
 *
 * @property int $id
 * @property float $value
 * @property int $payer_id
 * @property int $payee_id
 * -
 * @property User $payer
 * @property User $payee
 */
class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'value',
        'payer_id',
        'payee_id'
    ];

    protected $casts = [
        'value' => 'float'
    ];

    public $timestamps = false;

    public function payer()
    {
        return $this->hasOne(User::class, 'id', 'payer_id');
    }

    public function payee()
    {
        return $this->hasOne(User::class, 'id', 'payee_id');
    }
}
