<?php

namespace MFebriansyah\LaravelAPIManager;

use Illuminate\Support\ServiceProvider;

class LaravelAPIManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        require dirname(__FILE__).'/config/constants.php';
    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }
}
