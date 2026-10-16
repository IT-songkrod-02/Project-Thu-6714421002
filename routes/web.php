<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReelController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

// Landing = Login
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/my-reels', [ReelController::class, 'mine'])->name('reels.mine');
    Route::resource('reels', ReelController::class);

    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/reels/{reel}/favorite', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

    Route::post('/sellers/{seller}/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{reel}/{user}', [ChatController::class, 'show'])->name('chat.show');
});