<?php

use App\Http\Controllers\AccountController;
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

Route::prefix('account')->name('account.')->group(
    static function () {
        Route::get('/enter', [AccountController::class, 'showEnterView'])->name('enter');
        Route::post('/enter', [AccountController::class, 'enter']);

        Route::get('/deposit', [AccountController::class, 'showDepositView'])->name('deposit');
        Route::post('/deposit', [AccountController::class, 'deposit']);

        Route::get('/transactions', [AccountController::class, 'transactions'])->name('transactions');
        Route::get('/deposits', [AccountController::class, 'deposits'])->name('deposits');
    }
);
