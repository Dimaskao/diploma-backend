<?php

// use App\Http\Controllers\CompaniesController;
// use App\Http\Controllers\JobOffersController;
// use App\Http\Controllers\SkillsController;
// use App\Http\Controllers\UsersController;
// use Illuminate\Support\Facades\Route;

// Route::resource('users', UsersController::class);

// Route::get('skills', [SkillsController::class, 'index']);
// Route::get('skills/{id}', [SkillsController::class, 'show']);

// Route::get('companies', [CompaniesController::class, 'index']);
// Route::get('companies/{id}', [CompaniesController::class, 'show']);

// Route::get('job-offers', [JobOffersController::class, 'index']);
// Route::get('job-offers/{id}', [JobOffersController::class, 'show']);


use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;

// Register a new user or company
Route::post('/register', [UserProfileController::class, 'register']);

// Log in user or company
Route::post('/login', [UserProfileController::class, 'login']);

// Log out user or company
Route::post('/logout', [UserProfileController::class, 'logout']);

// Display the specified user or company profile
Route::get('/profile/{id}', [UserProfileController::class, 'show']);

// Update the specified user or company profile
Route::put('/profile/{id}', [UserProfileController::class, 'update']);

// Add contact information to user or company profile
Route::post('/profile/{id}/contact', [UserProfileController::class, 'addContactInfo']);