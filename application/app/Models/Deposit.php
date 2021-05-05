<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Deposit extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    const MAX_ACCRUE_TIMES = 10;

    const DEFAULT_PERCENT = 20;
    const DEFAULT_DURATION = 10;

    public function accrueAmount(): float
    {
        return (float)$this->hasMany(Transaction::class)->where('type', Transaction::TYPE_ACCRUE)->sum('amount');
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class, 'id', 'wallet_id');
    }

    /**
     * @return bool
     */
    public function processPayout()
    {
        // create new transaction
        $transaction = new Transaction();
        $transaction->user_id = $this->user_id;
        $transaction->wallet_id = $this->wallet_id;
        $transaction->deposit_id = $this->id;
        $transaction->amount = $this->invested * ($this->percent / 100);

        if ($this->accrue_times < self::MAX_ACCRUE_TIMES - 1) {
            $transaction->type = Transaction::TYPE_ACCRUE;
        } else {
            $transaction->type = Transaction::TYPE_CLOSE_DEPOSIT;
            $this->active = false;
        }

        // update wallet's balance
        $wallet = $this->wallet;
        $wallet->balance += $transaction->amount;

        // update accrue iterator
        $this->accrue_times += 1;

        $deposit = $this;

        // save data
        return DB::transaction(
            static function () use ($deposit, $transaction, $wallet) {
                $deposit->save();
                $transaction->save();
                $wallet->save();

                return true;
            }
        ) ?? false;
    }

    public static function allActive()
    {
        return self::query()->where('active', true)->get();
    }
}
