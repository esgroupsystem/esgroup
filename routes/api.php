<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RecordController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| All routes here are prefixed with /api automatically.
| Keep authentication routes separate and secure.
|
*/

// Authentication
Route::post('/login', [AuthController::class, 'login'])->name('api.login');

// Records CRUD (requires authentication later with middleware)
Route::apiResource('records', RecordController::class);
