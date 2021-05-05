<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get(
    '/',
    function () {
        return view('welcome');
    }
);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/transactions', [TransactionController::class, 'transactions'])->name('transactions');
Route::get('/transactions/enter', [TransactionController::class, 'showEnterView'])->name('transactions.form');
Route::post('/transactions/enter', [TransactionController::class, 'enter'])->name('transactions.enter');

Route::get('/deposits', [DepositController::class, 'deposits'])->name('deposits');
Route::get('/deposits/create', [DepositController::class, 'showDepositView'])->name('deposits.form');
Route::post('/deposits/create', [DepositController::class, 'create'])->name('deposit.create');
