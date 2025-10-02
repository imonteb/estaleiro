<?php

namespace App\Providers;

use App\Policies\TeamPolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
            /* if (config('app.env') === 'local') {
                $this->app['request']->server->set('HTTPS', true);

            } */
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}
