<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepositRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class DepositController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function deposits(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        $deposits = $user->deposits()->get()->all();
        return view('account.deposits', ['deposits' => $deposits]);
    }

    public function create(DepositRequest $request)
    {
        $amount = (float)$request->get('amount');

        /** @var User $user */
        $user = auth()->user();
        if ($user->createDeposit($amount)) {
            Session::flash('message', 'Deposit was created!');
        } else {
            Session::flash('error', 'Error while create deposit.');
        }

        return redirect(route('deposits.form'));
    }

    public function showDepositView(Request $request)
    {
        return view('account.deposit');
    }
}
