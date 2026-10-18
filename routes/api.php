<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Middleware\SetApiLocale;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\GuestController;
use App\Http\Controllers\Api\ContactUsController;
use App\Http\Controllers\Api\PageController;

Route::middleware(SetApiLocale::class)->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');

    Route::controller(AuthController::class)->group(function () {
        Route::post('/register', 'register');
        Route::post('/login', 'login');
        Route::delete('/logout', 'logout');
        Route::post('/forgot-password', 'forgotPassword')->middleware('throttle:5,1');
    });

    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:5,1');

    Route::middleware('auth:sanctum')->group(function () {
        Route::delete('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::match(['put', 'patch'], '/profile', [AuthController::class, 'updateProfile']);
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
