<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\VCardController;

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

Route::get('vcards', [VCardController::class, 'index']);
Route::post('vcards', [VCardController::class, 'store']);
Route::get('vcards/me', [VCardController::class, 'show_me']);
Route::get('vcards/{vcard}', [VCardController::class, 'show']); //->middleware('can:view,vcard');
Route::put('vcards/{vcard}', [VCardController::class, 'update']); //->middleware('can:update,vcard');
Route::patch('vcards/{vcard}/password', [VCardController::class, 'update_password']); //->middleware('can:updatePassword,vcard');
Route::delete('vcards/{vcard}', [VCardController::class, 'destroy']);
