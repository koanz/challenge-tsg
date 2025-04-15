<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostRestController;
use App\Http\Controllers\UserRestController;
use App\Http\Middleware\CanCreatePost;
use App\Http\Middleware\CanCreateUser;
use App\Http\Middleware\JwtTokenAuthenticate;
use App\Http\Middleware\CanDeleteUser;
use App\Http\Middleware\VerifyPostOwner;
use App\Http\Middleware\CanUpdateUser;
use Illuminate\Support\Facades\Route;

// Rutas protegidas con token de acceso JWT
Route::middleware([JwtTokenAuthenticate::class])->group(function () {
    // Rutas de usuario
    Route::group(['middleware' => ['role_or_permission:admin|users']], function () {
        Route::get('/users', [UserRestController::class, 'index'])
            ->name('user-index');

        Route::post('/users', [UserRestController::class, 'create'])
            ->middleware(CanCreateUser::class)
            ->name('user-create');

        Route::get('/users/{id}', [UserRestController::class, 'find'])
            ->name('user-find');

        Route::patch('/users/{id}', [UserRestController::class, 'update'])
            ->middleware(CanUpdateUser::class)
            ->name('user-update');
    });

    Route::delete('/users/{id}', [UserRestController::class, 'delete'])
        ->middleware([CanDeleteUser::class])
        ->name('user-delete');

    // Rutas de post
    Route::get('/posts', [PostRestController::class, 'index'])
        ->name('post-index');

    Route::post('/posts', [PostRestController::class, 'create'])
        ->middleware(CanCreatePost::class)
        ->name('post-create');

    Route::get('/posts/{id}', [PostRestController::class, 'find'])
        ->name('post-find');

    Route::patch('/posts/{id}', [PostRestController::class, 'update'])
        ->middleware(VerifyPostOwner::class)
        ->name('post-update');

    Route::delete('/posts/{id}', [PostRestController::class, 'delete'])
        ->middleware(VerifyPostOwner::class)
        ->name('post-delete');

    // Logout: Invalidación de token actual
    Route::post('/logout', [AuthController::class, 'logout'])
        ->middleware('auth:api')
        ->name('logout');
});

// Rutas públicas sin verificación de token
Route::post('/register', [AuthController::class, 'register'])
    ->name('register');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login');
