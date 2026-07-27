<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::controller(AuthController::class)->group(function () {

    Route::post('/register', 'register');

    Route::post('/login', 'login');
    Route::delete('/logout', 'logout');
    Route::post('/forgot-password', 'forgotPassword');
    Route::post('/reset-password', 'resetPassword');
});

Route::controller(ProductController::class)->group(function () {
    Route::get('/products', 'index');
    Route::get('/products/{id}', 'show');
});


Route::get('/reset-password/{token}', function ($token) {
    return $token;
})->name('password.reset');
