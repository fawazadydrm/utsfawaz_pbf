<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;

// Routes untuk autentikasi
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('auth/google', [AuthController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

// Routes public untuk hanya melihat product
Route::get('product/{id}', [ProductController::class, 'show']);
Route::get('products', [ProductController::class, 'index']);

// Routes protected dengan middleware check.jwt
Route::middleware(['check.jwt'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('profile', [AuthController::class, 'profile']);

    // Routes untuk user yang hanya diperbolehkan untuk read saja
    Route::get('user/{id}', [UserController::class, 'show']);
    Route::get('users', [UserController::class, 'index']);

    // Routes untuk CRUD product
    Route::group(['middleware' => ['role:user,admin']], function () {
        Route::post('product', [ProductController::class, 'store']);
        Route::put('product/{id}', [ProductController::class, 'update']);
        Route::delete('product/{id}', [ProductController::class, 'destroy']);
    });

    // Routes untuk CRUD category dan melihat category (hanya untuk admin)
    Route::middleware(['role:admin'])->group(function () {
        Route::get('category/{id}', [CategoryController::class, 'show']);
        Route::get('categories', [CategoryController::class, 'index']);
        Route::post('category', [CategoryController::class, 'store']);
        Route::put('category/{id}', [CategoryController::class, 'update']);
        Route::delete('category/{id}', [CategoryController::class, 'destroy']);
    });
});
