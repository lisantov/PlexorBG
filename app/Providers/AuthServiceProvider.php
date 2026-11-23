<?php

namespace App\Providers;

use App\Services\Auth\AuthenticationService;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register authentication domain services.
     */
    public function register(): void
    {
        $this->app->singleton(AuthenticationService::class);
    }

    public function boot(): void
    {
        //
    }
}
