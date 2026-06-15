<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Booking;
use App\Observers\BookingObserver;
use Illuminate\Support\Facades\URL; // <-- Tambahkan baris ini

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void 
    {
        // Tetap pertahankan observer booking Anda
        Booking::observe(BookingObserver::class);

        // Tambahkan ini: Paksa semua link asset (CSS/JS) menggunakan HTTPS jika di Railway
        if (env('APP_ENV') === 'production' || env('RAILWAY_STATIC_URL')) {
            URL::forceScheme('https');
        }
    }
}