<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoucherController;
use App\Http\Middleware\TokenValidationMiddleware;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Users
Route::post('/user/create', [UserController::class, 'store']);
Route::post('/user/login', [UserController::class, 'login']);

// Vouchers
Route::post('/voucher/create', [VoucherController::class, 'store'])
    ->middleware(TokenValidationMiddleware::class);
Route::post('/voucher/delete', [VoucherController::class, 'delete'])
    ->middleware(TokenValidationMiddleware::class);
Route::get('/vouchers', [VoucherController::class, 'userVouchers'])
    ->middleware(TokenValidationMiddleware::class);