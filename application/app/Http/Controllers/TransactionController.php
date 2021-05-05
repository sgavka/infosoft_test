<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showEnterView(Request $request)
    {
        return view('account.enter');
    }

    public function enter(TransactionRequest $request)
    {
        /** @var User $user */
        $user = auth()->user();

        $amount = (float)$request->get('amount');
        if ($user->updateBalance($amount)) {
            Session::flash('message', 'Balance was updated!');
        } else {
            Session::flash('error', 'Error while update balance.');
        }

        return redirect(route('transactions.form'));
    }

    public function transactions(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        $transactions = $user->transactions()->get()->all();
        return view('account.transactions', ['transactions' => $transactions]);
    }
}
