<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        \Illuminate\Support\Facades\Blade::if('canpage', function ($page, $action = 'view', $firmId = null) {
            return auth()->check() && auth()->user()->hasPagePermission($page, $action, $firmId);
        });
    }
}
