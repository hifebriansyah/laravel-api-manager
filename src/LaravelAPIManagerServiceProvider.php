<?php
 
namespace MFebriansyah\LaravelAPIManager;
 
use Illuminate\Support\ServiceProvider;
 
class LaravelAPIManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        require dirname(__FILE__).'/config/constants.php';
    }
 
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}