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

Route::get('/auth/user/profile', [\App\Http\Controllers\ProfileController::class, 'getAuthUserProfile']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/user/{id}', [\App\Http\Controllers\UserController::class, 'getById']);

    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index']);
    Route::post('/profile/store', [\App\Http\Controllers\ProfileController::class, 'store']);


    Route::get('/posts', [\App\Http\Controllers\PostController::class, 'index']);
    Route::post('/posts', [\App\Http\Controllers\PostController::class, 'store']);
    Route::get('/user/{id}/posts', [\App\Http\Controllers\PostController::class, 'getCertainUserPosts']);
    Route::get('/post/{id}', [\App\Http\Controllers\PostController::class, 'getCertainPost']);
    Route::get('/post/{id}/show', [\App\Http\Controllers\PostController::class, 'show']);
    Route::patch('/posts/{post}', [\App\Http\Controllers\PostController::class, 'update']);
    Route::delete('/posts/{post}', [\App\Http\Controllers\PostController::class, 'destroy']);


    Route::post('/likes', [\App\Http\Controllers\LikeController::class, 'store']);
    Route::delete('/likes/{like}', [\App\Http\Controllers\LikeController::class, 'destroy']);

    Route::post('/comments', [\App\Http\Controllers\CommentController::class, 'store']);
    Route::delete('/comments/{comment}', [\App\Http\Controllers\CommentController::class, 'destroy']);
});
