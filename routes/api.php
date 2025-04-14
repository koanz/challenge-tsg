<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostRestController;
use App\Http\Controllers\UserRestController;
use App\Http\Middleware\JwtTokenAuthenticate;
use App\Http\Middleware\VerifyPostOwner;
use Illuminate\Support\Facades\Route;

// Rutas protegidas con token de acceso JWT
Route::middleware([JwtTokenAuthenticate::class])->group(function () {
    Route::get('/users', [UserRestController::class, 'index']);

    Route::get('/posts', [PostRestController::class, 'index']);
    Route::post('/posts', [PostRestController::class, 'create']);
    Route::get('/posts/{id}', [PostRestController::class, 'find']);
    Route::patch('/posts/{id}', [PostRestController::class, 'update'])->middleware(VerifyPostOwner::class);
    Route::delete('/posts/{id}', [PostRestController::class, 'delete'])->middleware(VerifyPostOwner::class);
});

// Rutas públicas sin verificación de Token JWT
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
