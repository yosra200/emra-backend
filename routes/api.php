<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Middleware\SetApiLocale;

Route::middleware(SetApiLocale::class)->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');

    Route::controller(AuthController::class)->group(function () {
        Route::post('/register', 'register');
        Route::post('/login', 'login');
        Route::delete('/logout', 'logout');
        Route::post('/forgot-password', 'forgotPassword');
    });

    Route::middleware('auth:sanctum')->controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'show');
        Route::patch('/profile', 'update');
    });
});
