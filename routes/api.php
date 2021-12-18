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
use App\Http\Controllers\api\PdfController;
use App\Http\Controllers\api\ContactController;

Route::post('login', [AuthController::class, 'login']);
Route::post('vcards', [VCardController::class, 'store']);

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('vcards/{vcard}/transactions', [TransactionController::class, 'getTransactionsOfVCard'])->middleware('can:viewTransactionsOfVCard,vcard');
    Route::get('transactions/{transaction}', [TransactionController::class, 'show'])->middleware('can:view,transaction');
    Route::post('transactions', [TransactionController::class, 'store'])->middleware('can:create,App\Models\Transaction');
    Route::patch('transactions/{transaction}', [TransactionController::class, 'update'])->middleware('can:update,transaction');
    Route::delete('transactions/{transaction}', [TransactionController::class, 'delete'])->middleware('can:destroy,transaction');

    // CATEGORIES
    Route::get('vcards/{vcard}/categories', [CategoryController::class, 'getCategoriesOfVCard'])->middleware('can:viewCategoriesOfVCard,vcard');
    Route::get('vcards/{vcard}/categories/all', [CategoryController::class, 'getAllCategoriesOfVCard'])->middleware('can:viewCategoriesOfVCard,vcard');
    Route::get('categories/{category}', [CategoryController::class, 'show'])->middleware('can:view,category');
    Route::post('categories', [CategoryController::class, 'store'])->middleware('can:create,App\Models\Category');
    Route::put('categories/{category}', [CategoryController::class, 'update'])->middleware('can:update,category');
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->middleware('can:destroy,category');

    // DEFAULT CATEGORIES
    Route::get('defaultCategories', [DefaultCategoryController::class, 'index'])->middleware('can:viewAny,App\Models\DefaultCategory');
    Route::get('defaultCategories/{defaultCategory}', [DefaultCategoryController::class, 'show'])->middleware('can:view,defaultCategory');
    Route::post('defaultCategories', [DefaultCategoryController::class, 'store'])->middleware('can:viewAny,App\Models\DefaultCategory');
    Route::put('defaultCategories/{defaultCategory}', [DefaultCategoryController::class, 'update'])->middleware('can:update,defaultCategory');
    Route::delete('defaultCategories/{defaultCategory}', [DefaultCategoryController::class, 'destroy'])->middleware('can:destroy,defaultCategory');

    // VCARDS
    Route::get('vcards', [VCardController::class, 'index'])->middleware('can:viewAny,App\Models\VCard',);
    Route::get('vcards/{vcard}/contacts', [VCardController::class, 'show_contacts'])->middleware('can:viewContactsOfVCard,vcard');
    Route::get('vcards/{vcard}', [VCardController::class, 'show'])->middleware('can:view,vcard');
    Route::put('vcards/{vcard}', [VCardController::class, 'update'])->middleware('can:update,vcard');
    Route::patch('vcards/{vcard}/password', [VCardController::class, 'update_password'])->middleware('can:updatePassword,vcard');
    Route::patch('vcards/{vcard}/code', [VCardController::class, 'update_code'])->middleware('can:updateCode,vcard');
    Route::patch('vcards/{vcard}/blocked', [VCardController::class, 'update_blocked'])->middleware('can:updateBlock,vcard');
    Route::delete('vcards/{vcard}', [VCardController::class, 'destroy'])->middleware('can:delete,vcard');
    Route::post('vcards/{vcard}/photo', [VCardController::class, 'update_photo'])->middleware('can:updatePhoto,vcard');


    //USERS
    //Route::get('users', [UserController::class, 'index']);
    Route::get('users/me', [UserController::class, 'show_me'])->middleware('can:view,App\Models\User');
    //Route::get('users/{user}', [UserController::class, 'show']);
    Route::get('paymenttypes', [PaymentTypeController::class, 'index']);

    //ADMINISTRATORS
    Route::get('administrators', [AdministratorController::class, 'index'])->middleware('can:viewAny,App\Models\Administrator');
    Route::get('administrators/{administrator}', [AdministratorController::class, 'show'])->middleware('can:view,administrator');
    Route::put('administrators/{administrator}', [AdministratorController::class, 'update'])->middleware('can:update,administrator');
    Route::post('administrators', [AdministratorController::class, 'store'])->middleware('can:create,App\Models\Administrator');
    Route::patch('administrators/{administrator}/password', [AdministratorController::class, 'update_password'])->middleware('can:updatePassword,administrator');
    Route::delete('administrators/{administrator}', [AdministratorController::class, 'delete'])->middleware('can:delete,administrator');

    //STATISTICS
    Route::get('statistics/sumbymonthyear', [StatisticsController::class, 'sumbymonthyear']);
    Route::get('statistics/sumbymonthyear/{year}', [StatisticsController::class, 'sumbymonthyearFilterYear']);
    Route::get('statistics/countpaymentype', [StatisticsController::class, 'countPaymentType']);
    Route::get('statistics/countpaymentype/{year}', [StatisticsController::class, 'countPaymentTypeFilterYear']);
    Route::get('statistics/counters', [StatisticsController::class, 'counters']);
    Route::get('statistics/categories', [StatisticsController::class, 'categories']);
    Route::get('statistics/categories/{year}', [StatisticsController::class, 'categoriesFilterYear']);
    Route::get('statistics/years', [StatisticsController::class, 'years']);
    Route::get('statistics/balanceovertime', [StatisticsController::class, 'balanceOverTime']);

    //PDF
    Route::get('pdf/{transaction}', [PdfController::class, 'index'])->name('pdf.index');

    //CONTACTS
    Route::get('contacts/{contact}', [ContactController::class, 'show'])->middleware('can:view,contact');
    Route::post('contacts', [ContactController::class, 'store'])->middleware('can:create,App\Models\Contact');
    Route::put('contacts/{contact}', [ContactController::class, 'update'])->middleware('can:update,contact');
    Route::delete('contacts/{contact}', [ContactController::class, 'destroy'])->middleware('can:destroy,contact');
});
