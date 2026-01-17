<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Booking;
use App\Observers\BookingObserver;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void {
        Booking::observe(BookingObserver::class);
    }
}