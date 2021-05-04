<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    public function accrueAmount(): float
    {
        return (float)$this->hasMany(Transaction::class)->where('type', Transaction::TYPE_ACCRUE)->sum('amount');
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class, 'id', 'wallet_id');
    }
}
