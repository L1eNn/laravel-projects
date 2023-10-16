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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::middleware(['web', 'auth'])->get('/user', function () {
    return \Illuminate\Support\Facades\Auth::user();
});

Route::middleware(['web', 'auth'])->group(function () {
    Route::post('/baskets', [\App\Http\Controllers\BasketController::class, 'store'])->name('baskets.store');
    Route::delete('/baskets/{basket}', [\App\Http\Controllers\BasketController::class, 'destroy'])->name('baskets.destroy');

    Route::post('/orders.store', [\App\Http\Controllers\OrderController::class, 'store'])->name('orders.store');
});

Route::middleware(['web', 'auth', 'admin'])->prefix('admin')->group(function () {
    Route::post('/goods', [\App\Http\Controllers\GoodController::class, 'store'])->name('goods.store');
    Route::patch('/goods/{good}', [\App\Http\Controllers\GoodController::class, 'update'])->name('goods.update');
    Route::delete('/goods/{good}', [\App\Http\Controllers\GoodController::class, 'destroy'])->name('goods.destroy');

    Route::patch('/orders/{order}',[\App\Http\Controllers\OrderController::class, 'update'])->name('orders.update');
});
