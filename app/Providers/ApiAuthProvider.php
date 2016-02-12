<?php namespace App\Providers;

use App\User;
use App\Auth\ApiUserProvider;
use Illuminate\Support\ServiceProvider;

class ApiAuthProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['auth']->extend('api', function()
        {
            return new ApiUserProvider(new User);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

}
