<?php

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

// Route::get('/', function () {
//     return view('dashboard');
// });

Route::get('login', 'AuthController@index')->name('login-view');
Route::post('login', 'AuthController@login')->name('login');

Route::group(['middleware' => ['auth']], function () {

    Route::get('/', 'TransactionController@index');
    Route::post('create_transaction', 'TransactionController@store')->name('create_transaction');
    Route::get('report', 'TransactionController@report')->name('report');
    Route::get('logout', 'AuthController@logout')->name('logout');
    Route::post('get_all_report', 'TransactionController@get_all_report')->name('get_all_report');
    Route::get('changeStatus', 'TransactionController@changeStatus');
    Route::get('wallet_request', 'WalletController@index');
    Route::post('get_wallet_data_send', 'WalletController@get_wallet_data_send');
    Route::post('submit_wallet_request', 'WalletController@submit_wallet_request');
    Route::post('accept_wallet_request', 'WalletController@accept_wallet_request');
    Route::post('decline_wallet_request', 'WalletController@decline_wallet_request');
    Route::post('delete_transaction', 'TransactionController@deleteTransaction');
    Route::get('user/{type}', 'UserController@index');
    Route::get('user_active_status_update/{id}', 'UserController@user_active_status_update');
    Route::get('general_notification_count', 'TransactionController@general_notification_count');
});
Route::get('test', 'TransactionController@test');
