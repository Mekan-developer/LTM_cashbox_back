<?php

use App\Http\Controllers\Api\AnalyticsController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CashboxController;
use App\Http\Controllers\Api\CurrencyController;
use App\Http\Controllers\Api\ExchangeRateController;
use App\Http\Controllers\Api\RecordController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
});



Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/users/{user}/edit', [AuthController::class, 'edit']);
    Route::patch('/users/{user}', [AuthController::class, 'update']);
    Route::get('/users', [AuthController::class, 'users']);
    Route::delete('/users/{user}', [AuthController::class, 'destroy']);


    Route::apiResource('exchange-rates', ExchangeRateController::class);
    Route::apiResource('cashboxes', CashboxController::class);
    Route::apiResource('currencies', CurrencyController::class);
    Route::apiResource('records', RecordController::class);
    Route::get('/analytics', [AnalyticsController::class, 'index']);
    Route::get('/analytics/summary', [AnalyticsController::class, 'summary']);


    Route::get('/roles', [RoleController::class, 'index']);



    // сюда позже добавим Excel экспорт и аналитику
});


Route::fallback(function () {
    return 'this route not find ';
});
