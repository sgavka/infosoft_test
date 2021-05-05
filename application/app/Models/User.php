<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'login',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function wallet(): Wallet
    {
        return $this->hasMany(Wallet::class)->first();
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    /**
     * @param float $amount
     * @return bool
     */
    public function updateBalance(float $amount): bool
    {
        $wallet = $this->wallet();

        // create transaction
        $transaction = new Transaction();
        $transaction->user_id = $this->id;
        $transaction->wallet_id = $wallet->id;
        $transaction->type = Transaction::TYPE_ENTER;
        $transaction->amount = $amount;

        // update wallet balance
        $wallet->balance += $amount;

        // save data
        return DB::transaction(
            static function () use ($transaction, $wallet) {
                $transaction->save();
                $wallet->save();

                return true;
            }
        ) ?? false;
    }

    /**
     * @param float $amount
     * @return bool
     */
    public function createDeposit(float $amount): bool
    {
        $wallet = $this->wallet();

        // create deposit
        $deposit = new Deposit();
        $deposit->user_id = $this->id;
        $deposit->wallet_id = $wallet->id;
        $deposit->invested = $amount;
        $deposit->percent = Deposit::DEFAULT_PERCENT;
        $deposit->duration = Deposit::DEFAULT_DURATION;
        $deposit->active = true;

        // create transaction
        $transaction = new Transaction();
        $transaction->user_id = $this->id;
        $transaction->wallet_id = $wallet->id;
        $transaction->type = Transaction::TYPE_CREATE_DEPOSIT;
        $transaction->amount = $amount;

        // update wallet balance
        $wallet->balance -= $amount;

        // save data
        return DB::transaction(
            static function () use ($deposit, $transaction, $wallet) {
                $deposit->save();

                // set deposit in transaction
                $transaction->deposit_id = $deposit->id;
                $transaction->save();

                $wallet->save();

                return true;
            }
        ) ?? false;
    }
}
