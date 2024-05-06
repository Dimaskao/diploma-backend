<?php

use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\JobOffersController;
use App\Http\Controllers\SkillsController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

// api/...
Route::get('users', [UsersController::class, 'index']);
Route::get('users/{id}', [UsersController::class, 'show']);
Route::post('users/create', [UsersController::class, 'store']);

Route::get('skills', [SkillsController::class, 'index']);
Route::get('skills/{id}', [SkillsController::class, 'show']);

Route::get('companies', [CompaniesController::class, 'index']);
Route::get('companies/{id}', [CompaniesController::class, 'show']);

Route::get('job-offers', [JobOffersController::class, 'index']);
Route::get('job-offers/{id}', [JobOffersController::class, 'show']);
