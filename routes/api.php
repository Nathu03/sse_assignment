<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by the RouteServiceProvider and will be assigned
| to the "api" middleware group. Build your API here!
|
*/

// Example auth route
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Blog post routes
Route::get('/posts', [PostController::class, 'index']);
Route::post('/posts', [PostController::class, 'store']);
Route::put('/posts/{id}', [PostController::class, 'update']);
Route::delete('/posts/{id}', [PostController::class, 'destroy']);

// Post priority route
Route::post('/posts/{id}/priority', [PostController::class, 'setPriority']);
Route::post('/posts/{id}/priority', [PostController::class, 'updatePriority']);

