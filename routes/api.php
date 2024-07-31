<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostsFeedController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialNetworkController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/profile/{id}', [ProfileController::class, 'show']);
    Route::put('/profile/{id}', [ProfileController::class, 'update']);
    Route::delete('/profile/{id}', [ProfileController::class, 'destroy']);

    Route::post('/social.network/{id}/subscribe', [SocialNetworkController::class, 'subscribe']);
    Route::post('/social.network/{id}/unsubscribe', [SocialNetworkController::class, 'unsubscribe']);
    Route::post('/social.network/send.message', [SocialNetworkController::class, 'sendMessage']);
    Route::get('/social.network/search', [SocialNetworkController::class, 'search']);
    Route::get('/social.network/get.messages/{chatId}', [SocialNetworkController::class, 'getMessages']);

    Route::apiResource('posts', PostController::class);
    Route::get('/posts-feed/{userId}', PostsFeedController::class);
    Route::get('/profile/{userId}/posts', [\App\Http\Controllers\User\PostController::class, 'index']);
});
