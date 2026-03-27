<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/webhooks/xendit', [\App\Http\Controllers\Api\XenditWebhookController::class, 'handleInvoice']);

Route::get('/membership/check-nik', [\App\Http\Controllers\Api\MembershipCheckController::class, 'checkNik']);
Route::get('/membership/check-voucher', [\App\Http\Controllers\Api\MembershipCheckController::class, 'checkVoucher']);
