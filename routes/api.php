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


use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Register a new user or company
Route::post('/register', [AuthController::class, 'register']);

// Log in user or company
Route::post('/login', [AuthController::class, 'login']);

// Log out user or company
Route::post('/logout', [AuthController::class, 'logout']);

// Display the specified user or company profile
Route::get('/profile/{id}', [AuthController::class, 'show']);

// Update the specified user or company profile
Route::put('/profile/{id}', [AuthController::class, 'update']);

// Add contact information to user or company profile
Route::post('/profile/{id}/contact', [AuthController::class, 'addContactInfo']);