<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;

// PUBLIC
Route::get('/', [FrontendController::class, 'index'])->name('index');
Route::get('/login', [FrontendController::class, 'login'])->name('login');
Route::get('/register', [FrontendController::class, 'register'])->name('register');

// AUTH
Route::post('/register/store', [AuthController::class, 'register'])->name('register.store');
Route::post('/login/authenticate', [AuthController::class, 'login'])->name('login.authenticate');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// USER DASHBOARD (hanya user)
Route::middleware(['auth', \App\Http\Middleware\RoleUser::class])->group(function () {
    Route::get('/user/dashboard', [BookingController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/booking', [BookingController::class, 'create'])->name('booking');
    Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');
    Route::delete('/booking/{id}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');
    Route::get('/user/profile', [AuthController::class, 'editProfile'])->name('user.profile');
    Route::post('/user/profile/update', [AuthController::class, 'updateProfile'])->name('user.profile.update');
});