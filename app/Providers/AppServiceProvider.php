<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\OpenMeteoService;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('dashboard', function ($view) {
            $view->with('clima', app(OpenMeteoService::class)->obtenerClimaActual());
        });
    }
}
