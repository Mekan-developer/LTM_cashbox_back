<?php

use App\Http\Controllers\Api\AnalyticsController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CashboxController;
use App\Http\Controllers\Api\CurrencyController;
use App\Http\Controllers\Api\ExchangeRateController;
use App\Http\Controllers\Api\RecordController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', 'login');
});



Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);


    Route::apiResource('exchange-rates', ExchangeRateController::class);
    Route::apiResource('cashboxes', CashboxController::class);
    Route::apiResource('currencies', CurrencyController::class);
    Route::apiResource('records', RecordController::class);
    Route::get('/analytics', [AnalyticsController::class, 'index']);
    Route::get('/analytics/summary', [AnalyticsController::class, 'summary']);


    // сюда позже добавим Excel экспорт и аналитику
});



Route::fallback(function () {
    return 'this route not find ';
});
