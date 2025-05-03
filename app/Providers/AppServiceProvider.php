<?php

namespace App\Providers;

use App\Policies\MentorPolicy;
use App\Services\BookingService;
use App\Services\ReviewService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(BookingService::class, function ($app) {
            return new BookingService();
        });

        $this->app->bind(ReviewService::class, function ($app) {
            return new ReviewService();
        });
    }

    public function boot()
    {
        //
    }
}
