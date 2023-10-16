<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/', [\App\Http\Controllers\GoodController::class, 'index'])->name('goods.index');

Route::middleware('web', 'auth')->group(function () {
    Route::get('/basket/index', [\App\Http\Controllers\BasketController::class, 'index'])->name('basket.index');

    Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [\App\Http\Controllers\OrderController::class, 'create'])->name('orders.create');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/index', [\App\Http\Controllers\AdminController::class, 'index'])->name('admin.index');
    Route::get('/goods/create', [\App\Http\Controllers\GoodController::class, 'create'])->name('goods.create');
    Route::get('/goods/{good}/edit', [\App\Http\Controllers\GoodController::class, 'edit'])->name('goods.edit');

    Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'adminOrders'])->name('admin.orders');
});
