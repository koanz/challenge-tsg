<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostRestController;
use App\Http\Controllers\UserRestController;
use App\Http\Middleware\JwtTokenAuthenticate;
use App\Http\Middleware\VerifyPostOwner;
use Illuminate\Support\Facades\Route;

// Rutas protegidas con token de acceso JWT
Route::middleware([JwtTokenAuthenticate::class])->group(function () {
    Route::get('/users', [UserRestController::class, 'index'])->name('user-index');
    Route::post('/users', [UserRestController::class, 'create'])->name('user-create');
    Route::get('/users/{id}', [UserRestController::class, 'find'])->name('user-find');
    Route::patch('/users/{id}', [UserRestController::class, 'update'])->name('user-update');
    Route::delete('/users/{id}', [UserRestController::class, 'delete'])->name('user-delete');

    Route::get('/posts', [PostRestController::class, 'index'])->name('post-index');
    Route::post('/posts', [PostRestController::class, 'create'])->name('post-create');
    Route::get('/posts/{id}', [PostRestController::class, 'find'])->name('post-find');
    Route::patch('/posts/{id}', [PostRestController::class, 'update'])->middleware(VerifyPostOwner::class)->name('post-update');
    Route::delete('/posts/{id}', [PostRestController::class, 'delete'])->middleware(VerifyPostOwner::class)->name('post-delete');
});

// Rutas públicas sin verificación de Token JWT
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
