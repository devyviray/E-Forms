<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Route;

use Illuminate\Routing\Route;
use Illuminate\Support\ServiceProvider;

class RouteMacroServiceProvider extends ServiceProvider
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
        Route::macro('description', function (string $text) {
            $this->action['description'] = $text;
            return $this;
        });

        // PAGE // RESOURCE // ACTION // SYSTEM
        Route::macro('purpose', function (string $text) {
            $this->action['purpose'] = $text;
            return $this;
        });
    }
}
