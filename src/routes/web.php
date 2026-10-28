<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\DashboardController;

// トップページ
Route::get('/', function () {
    return redirect()->route('dashboard.index');
});

// ログインが必要なページ
Route::middleware('auth')->group(function () {

    // ダッシュボード
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard.index');

    // 食材管理
    Route::resource('foods', FoodController::class);

    // 在庫管理
    Route::get('/stocks', [StockController::class, 'index'])
        ->name('stocks.index');

    // 入庫（購入）
    Route::get('/stocks/in', [StockController::class, 'createIn'])
        ->name('stocks.in');

    Route::post('/stocks/in', [StockController::class, 'storeIn'])
        ->name('stocks.in.store');

    // 出庫（使用）
    Route::get('/stocks/out', [StockController::class, 'createOut'])
        ->name('stocks.out');

    Route::post('/stocks/out', [StockController::class, 'storeOut'])
        ->name('stocks.out.store');

    // 入出庫履歴
    Route::get('stocks/logs', [StockController::class, 'logs'])
        ->name('stocks.logs');
});