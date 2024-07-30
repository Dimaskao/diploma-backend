<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostsFeedController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/profile/{id}', [ProfileController::class, 'show']);
    Route::put('/profile/{id}', [ProfileController::class, 'update']);
    Route::post('/profile/{id}/subscribe', [ProfileController::class, 'subscribe']);
    Route::post('/profile/{id}/unsubscribe', [ProfileController::class, 'unsubscribe']);

    Route::get('/search', [ProfileController::class, 'search']);

    Route::apiResource('posts', PostController::class);
    Route::get('/posts-feed/{userId}', PostsFeedController::class);
    Route::get('/profile/{userId}/posts', [\App\Http\Controllers\User\PostController::class, 'index']);
});
