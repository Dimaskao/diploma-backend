<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\SkillsController;
use App\Http\Controllers\CompaniesController;

// api/...
Route::get('users', [UsersController::class, 'index']);
Route::get('users/{id}', [UsersController::class, 'show']);

Route::get('skills', [SkillsController::class, 'index']);
Route::get('skills/{id}', [SkillsController::class, 'show']);

Route::get('companies', [CompaniesController::class, 'index']);
Route::get('companies/{id}', [CompaniesController::class, 'show']);