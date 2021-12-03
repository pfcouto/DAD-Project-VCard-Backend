<?php

use App\Http\Controllers\api\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\VCardController;
use App\Http\Controllers\api\TransactionController;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\DefaultCategoryController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\PaymentTypeController;
use App\Http\Controllers\api\AdministratorController;
use App\Http\Controllers\api\StatisticsController;

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('vcards/{vcard}/transactions', [TransactionController::class, 'getTransactionsOfVCard']);
    Route::get('transactions', [TransactionController::class, 'index']);
    Route::get('transactions/{transaction}', [TransactionController::class, 'show']);
    Route::post('transactions', [TransactionController::class, 'store']);
    Route::patch('transactions/{transaction}', [TransactionController::class, 'update']);
    Route::delete('transactions/{transaction}', [TransactionController::class, 'delete']);

    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('categories/{category}', [CategoryController::class, 'show']);
    Route::post('categories', [CategoryController::class, 'store']);
    Route::put('categories/{category}', [CategoryController::class, 'update']);
    Route::delete('categories/{category}', [CategoryController::class, 'destroy']);

    Route::get('defaultCategories', [DefaultCategoryController::class, 'index']);/*->middleware('can:viewAny, user')*/
    Route::get('defaultCategories/{defaultCategory}', [DefaultCategoryController::class, 'show']);/*->middleware('can:view,user')*/
    Route::post('defaultCategories', [DefaultCategoryController::class, 'store']);
    Route::put('defaultCategories/{defaultCategory}', [DefaultCategoryController::class, 'update']);/*->middleware('can:update,user')*/
    Route::delete('defaultCategories/{defaultCategory}', [DefaultCategoryController::class, 'destroy']);/*->middleware('can:destroy,user')*/

    // Route::get('categories/{category}/transactions', [CategoryController::class, 'geTransactionsOfCategories']);
    Route::get('vcards', [VCardController::class, 'index']);
    Route::post('vcards', [VCardController::class, 'store']);
    Route::get('vcards/me', [VCardController::class, 'show_me']);
    Route::get('vcards/{vcard}', [VCardController::class, 'show']); //->middleware('can:view,vcard');
    Route::put('vcards/{vcard}', [VCardController::class, 'update']); //->middleware('can:update,vcard');
    Route::patch('vcards/{vcard}/password', [VCardController::class, 'update_password']); //->middleware('can:updatePassword,vcard');
    Route::patch('vcards/{vcard}/blocked', [VCardController::class, 'update_blocked']); //->middleware('can:updateBlocked,vcard');
    Route::delete('vcards/{vcard}', [VCardController::class, 'destroy']);

    //USERS
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/me', [UserController::class, 'show_me']);
    Route::get('users/{user}', [UserController::class, 'show']);

    Route::get('paymenttypes', [PaymentTypeController::class, 'index']);

    //ADMINISTRATORS
    Route::get('administrators', [AdministratorController::class, 'index']);
    Route::get('administrators/{administrator}', [AdministratorController::class, 'show']);
    Route::put('administrators/{administrator}', [AdministratorController::class, 'update']);
    Route::post('administrators', [AdministratorController::class, 'store']);
    Route::patch('administrators/{administrator}/password', [AdministratorController::class, 'update_password']);
    Route::delete('administrators/{administrator}', [AdministratorController::class, 'delete']);

    //STATISTICS
    Route::get('statistics/sumbymonthyear', [StatisticsController::class, 'sumbymonthyear']);
    Route::get('statistics/countpaymentype', [StatisticsController::class, 'countPaymentType']);
    Route::get('statistics/counters', [StatisticsController::class, 'counters']);

});
