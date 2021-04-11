<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(\App\Http\Middleware\Authenticate::class)->group(function () {
    Route::post('/transactions', 'TransactionsController@create')->name('transactions.create');
    Route::get('/users/{userId}/payments-types', 'UserController@paymentTypes')->name('user.payment_types.get');
    Route::get('/users/{userId}/balance', 'UserController@balance')->name('user.balance.get');
});

Route::get('/', 'IndexController@index')->name('healthcheck');
Route::post('/auth', 'AuthController@index')->name('auth');
Route::post('/users', 'UserController@create')->name('user.create');

