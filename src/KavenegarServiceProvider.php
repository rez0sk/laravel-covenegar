<?php


namespace Kavenegar;


use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class KavenegarServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('kavenegar', function() {
            return new Kavenegar(Config::get('services.kavenegar.api_key'));
        });
    }
}
