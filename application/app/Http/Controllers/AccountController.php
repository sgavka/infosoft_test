<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showEnterView(Request $request)
    {
        return view('account.enter');
    }

    public function enter(Request $request)
    {
        $this->enterValidator($request->all())->validate();

        /** @var User $user */
        $user = auth()->user();

        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $wallet = $user->wallet();
        $transaction->wallet_id = $wallet->id;
        $transaction->type = Transaction::TYPE_ENTER;
        $amount = $request->get('amount');
        $transaction->amount = $amount;

        if ($transaction->save()) {
            $wallet->balance += $amount;
            if ($wallet->save()) {
                Session::flash('message', 'Balance was updated!');
            }
        }

        return redirect(route('account.enter'));
    }

    /**
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function enterValidator(array $data)
    {
        return Validator::make(
            $data,
            [
                'amount' => ['required', 'numeric', 'min:0'],
            ]
        );
    }

    public function showDepositView(Request $request)
    {
        return view('account.deposit');
    }

    public function deposit(Request $request)
    {
        $this->depositValidator($request->all())->validate();

        /** @var User $user */
        $user = auth()->user();
        $wallet = $user->wallet();
        $amount = (float)$request->get('amount');

        $deposit = new Deposit();
        $deposit->user_id = $user->id;
        $deposit->wallet_id = $wallet->id;
        $deposit->invested = $amount;
        $deposit->percent = 20;
        $deposit->duration = 10;
        $deposit->active = true;

        if ($deposit->save()) {
            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->wallet_id = $wallet->id;
            $transaction->type = Transaction::TYPE_CREATE_DEPOSIT;
            $transaction->deposit_id = $deposit->id;

            $transaction->amount = $amount;

            if ($transaction->save()) {
                $wallet->balance -= $amount;
                if ($wallet->save()) {
                    Session::flash('message', 'Deposit was created!');
                }
            }
        }


        return redirect(route('account.deposit'));
    }

    /**
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function depositValidator(array $data)
    {
        return Validator::make(
            $data,
            [
                'amount' => ['required', 'numeric', 'min:10', 'max:100'],
            ]
        );
    }

    public function transactions(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        $transactions = $user->transactions()->get()->all();
        return view('account.transactions', ['transactions' => $transactions]);
    }

    public function deposits(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        $deposits = $user->deposits()->get()->all();
        return view('account.deposits', ['deposits' => $deposits]);
    }
}
