<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\FavoriteController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::controller(AuthController::class)->group(function () {

    Route::post('/register', 'register');

    Route::post('/login', 'login');
    Route::post('/forgot-password', 'forgotPassword');
    Route::post('/reset-password', 'resetPassword');

    Route::middleware('auth:sanctum')->group(function () {
        Route::delete('/logout', 'logout');
        Route::get('/profile', 'profile');
        Route::match(['put', 'patch'], '/profile', 'updateProfile');
    });
});

Route::controller(ProductController::class)->group(function () {
    Route::get('/products', 'index');
    Route::get('/products/{id}', 'show');
});


Route::controller(FavoriteController::class)->group(function () {
    Route::get('/favorites', 'getFavorites');
    Route::post('/favorites', 'addToFavorites');
    Route::delete('/favorites/{productId}', 'cancleFavorite');
});


Route::get('/reset-password/{token}', function ($token) {
    return $token;
})->name('password.reset');
