<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\GuestController;
use App\Http\Controllers\Api\ContactUsController;
use App\Http\Controllers\Api\PageController;

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


Route::post('/guest-token', [GuestController::class, 'generateToken']);


Route::controller(FavoriteController::class)->group(function () {
    Route::get('/favorites', 'getFavorites');
    Route::post('/favorites', 'addToFavorites');
    Route::delete('/favorites/{productId}', 'cancleFavorite');
});

Route::controller(ContactUsController::class)->group(function () {
    Route::post('/contact-us', 'store');
});

Route::controller(PageController::class)->group(function () {
    Route::get('/faqs', 'faqs');
    Route::get('/about-us', 'aboutUs');
});

Route::controller(CartController::class)->group(function () {
    Route::get('/cart', 'getCart');
    Route::post('/cart', 'addToCart');
    Route::match(['put', 'patch'], '/cart/{itemId}', 'updateCartItem');
    Route::delete('/cart/{itemId}', 'removeCartItem');
    Route::delete('/cart', 'clearCart');
});


Route::get('/reset-password/{token}', function ($token) {
    return $token;
})->name('password.reset');
