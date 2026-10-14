<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Flutter App Public API Endpoints
Route::get('/communities', [\App\Http\Controllers\Api\ApiAuthController::class, 'getCommunities']);
Route::post('/register', [\App\Http\Controllers\Api\ApiAuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\Api\ApiAuthController::class, 'login']);

