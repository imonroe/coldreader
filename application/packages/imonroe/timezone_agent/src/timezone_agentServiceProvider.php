<?php

namespace imonroe\timezone_agent;

use Illuminate\Support\ServiceProvider;

class timezone_agentServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // Migrations:
        $this->loadMigrationsFrom(__DIR__.'/migrations');

        // Views:
        $this->loadViewsFrom(__DIR__.'/views', 'timezone_agent');
        
        //$this->publishes([
        //	__DIR__.'/path/to/views' => resource_path('views/vendor/courier'),
        //]);

        // Routes:
        //$this->loadRoutesFrom(__DIR__.'/Http/routes.php');

        // echo( var_export($_COOKIE) );


        if ( !empty( $_COOKIE['coldreader_timezone'] ) ){
            config([ 'app.timezone' => $_COOKIE['coldreader_timezone'] ]);
        }


    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}