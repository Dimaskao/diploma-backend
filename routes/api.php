<?php

use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\JobOffersController;
use App\Http\Controllers\SkillsController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::resource('users', UsersController::class);

Route::get('skills', [SkillsController::class, 'index']);
Route::get('skills/{id}', [SkillsController::class, 'show']);

Route::get('companies', [CompaniesController::class, 'index']);
Route::get('companies/{id}', [CompaniesController::class, 'show']);

Route::get('job-offers', [JobOffersController::class, 'index']);
Route::get('job-offers/{id}', [JobOffersController::class, 'show']);
