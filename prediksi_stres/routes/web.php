<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;

// Home redirect
Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// User Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/questionnaire', [UserController::class, 'showQuestionnaire'])->name('questionnaire');
    Route::post('/questionnaire', [UserController::class, 'submitQuestionnaire'])->name('questionnaire.submit');
    Route::get('/prediction/{id}/result', [UserController::class, 'showResult'])->name('prediction.result');
    Route::get('/history', [UserController::class, 'history'])->name('history');
});

// Admin Routes
Route::middleware(['auth', \App\Http\Middleware\EnsureUserIsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{id}', [AdminController::class, 'userDetail'])->name('user.detail');
    Route::get('/predictions', [AdminController::class, 'predictions'])->name('predictions');
    Route::get('/predictions/{id}', [AdminController::class, 'predictionDetail'])->name('prediction.detail');
});
