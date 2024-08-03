<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialNetworkController;
use App\Http\Controllers\JobOffersController;
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

    /**
     * JobOffers Routes
     */
    Route::post('/job-offers', [JobOffersController::class, 'store']);
    Route::get('/job-offers/{id}', [JobOffersController::class, 'show']);
    Route::put('/job-offers/{id}', [JobOffersController::class, 'update']);
    Route::delete('/job-offers/{id}', [JobOffersController::class, 'destroy']);
    Route::get('/job-offers/by-company/{id}', [JobOffersController::class, 'getJobOffersByCompanyId']);

});
