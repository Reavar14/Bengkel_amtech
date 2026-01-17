<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\BookingReportController;
use App\Http\Middleware\RoleUser;
use App\Http\Controllers\ContactController;

/*
|--------------------------------------------------------------------------
| PUBLIC AREA
|--------------------------------------------------------------------------
*/
Route::get('/', [FrontendController::class, 'index'])
    ->name('index');

Route::get('/login', [FrontendController::class, 'login'])
    ->name('login');

Route::get('/register', [FrontendController::class, 'register'])
    ->name('register');

/*
|--------------------------------------------------------------------------
| AUTH ACTIONS
|--------------------------------------------------------------------------
*/
Route::post('/register/store', [AuthController::class, 'register'])
    ->name('register.store');

Route::post('/login/authenticate', [AuthController::class, 'login'])
    ->name('login.authenticate');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| USER AREA (ROLE: USER)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', RoleUser::class])->group(function () {

    Route::get('/user/dashboard', [BookingController::class, 'dashboard'])
        ->name('user.dashboard');

    Route::get('/booking', [BookingController::class, 'create'])
        ->name('booking');

    Route::post('/booking/store', [BookingController::class, 'store'])
        ->name('booking.store');

    Route::delete('/booking/{id}/cancel', [BookingController::class, 'cancel'])
        ->name('booking.cancel');

    Route::get('/booking/available-slots', [BookingController::class, 'availableSlots'])
        ->name('booking.slots');

    Route::get('/user/profile', [AuthController::class, 'editProfile'])
        ->name('user.profile');

    Route::post('/user/profile/update', [AuthController::class, 'updateProfile'])
        ->name('user.profile.update');
});

/*
|--------------------------------------------------------------------------
| ADMIN AREA (REPORT ONLY)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/report/booking/pdf', [BookingReportController::class, 'pdf'])
            ->name('booking.pdf');

        Route::get('/report/booking/excel', [BookingReportController::class, 'excel'])
            ->name('booking.excel');
});

Route::post('/kontak/kirim', [ContactController::class, 'send'])
    ->name('kontak.kirim');