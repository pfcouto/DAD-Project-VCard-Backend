<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\TransactionController;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\UserController;

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

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('users/me', [UserController::class, 'show_me']);
    Route::get('vcards/{vcard}/transactions', [TransactionController::class, 'getTransactionsOfVCard']);

    Route::get('transactions', [TransactionController::class, 'index']);
    Route::get('transactions/{transaction}', [TransactionController::class, 'show']);
    Route::post('transactions', [TransactionController::class, 'store']);
    Route::patch('transactions/{transaction}', [TransactionController::class, 'update']);
    Route::delete('transactions/{transaction}', [TransactionController::class, 'delete']);
});
