<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(AuthController::class)->middleware('guest')->group(function () {
    Route::post('/login', 'logInApi');
});

Route::middleware('auth:sanctum')->group(function() {
    Route::controller(AuthController::class)->group(function () {
        Route::get('/user/profile', 'getProfile');
        Route::post('/logout', 'logOutApi');
    });

    Route::controller(BookController::class)->group(function () {
        Route::get('/books/{book}', 'show');
        Route::get('/books', 'index');
        Route::post('/books', 'store');
    });
});
