<?php

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:sanctum')->group(function () {
    Route::get('getNewTransaction', 'TransactionController@getNewTransaction');
    Route::get('getPassTransaction', 'TransactionController@getPassTranasction');
    Route::get('getAllTransaction', 'TransactionController@getAllTransaction');
    Route::post('saveTransaction', 'TransactionController@saveTransaction');
    Route::post('passTransaction', 'TransactionController@passTransaction');
    Route::get('test', 'TransactionController@test');
});