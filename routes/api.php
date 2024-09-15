<?php

use App\Enums\UserRole;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\JobOffersController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostsFeedController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialNetworkController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    Route::resource('posts.comments', CommentController::class)->shallow();
    Route::get('/posts-feed/{userId}', PostsFeedController::class);
    Route::get('/profile/{userId}/posts', [\App\Http\Controllers\User\PostController::class, 'index']);

    //JobOffers Routes
    Route::post('/job-offers', [JobOffersController::class, 'store']);
    Route::get('/job-offers/{id}', [JobOffersController::class, 'show']);
    Route::get('/job-offers', [JobOffersController::class, 'index']);
    Route::put('/job-offers/{id}', [JobOffersController::class, 'update']);
    Route::delete('/job-offers/{id}', [JobOffersController::class, 'destroy']);
    Route::get('/job-offers/by-company/{id}', [JobOffersController::class, 'getJobOffersByCompanyId']);
    Route::post('/job-offers/subscribe/{id}/{id_}', [JobOffersController::class, 'subscribe']);
    Route::post('/job-offers/unsubscribe/{id}/{id_}', [JobOffersController::class, 'unsubscribe']);

    Route::get('/user', function() {
        $name = match (Auth::user()->role->name) {
            UserRole::REGULAR_USER => ['first_name' => Auth::user()->userProfile->regularUser->first_name, 'last_name' => Auth::user()->userProfile->regularUser->last_name],
            UserRole::COMPANY      => ['first_name' => Auth::user()->userProfile->company->name, 'last_name' => null],
            UserRole::ADMIN        => ['first_name' => Auth::user()->userProfile->admin->name, 'last_name' => null  ]
        };

        return [...Auth::user()->only('id', 'email'), 'role_name' => Auth::user()->role->name, ...$name];
    });

});
