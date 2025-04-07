<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ListingController;
use App\Http\Controllers\API\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::resource('listing', ListingController::class)->only([
    'index',
    'show'
]);

Route::post('/transaction/is-available', [TransactionController::class, 'isAvailable'])->middleware('auth:sanctum');
Route::resource('transaction', TransactionController::class)->only([
    'index',
    'store',
    'show',
])->middleware('auth:sanctum');
