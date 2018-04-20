<?php

namespace imonroe\cr_network_aspects;

use Illuminate\Support\ServiceProvider;

class cr_network_aspectsServiceProvider extends ServiceProvider
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
      //$this->loadViewsFrom(__DIR__.'/path/to/views', 'courier');
      //$this->publishes([
      //	__DIR__.'/path/to/views' => resource_path('views/vendor/courier'),
      //]);

      // Routes:
      $this->loadRoutesFrom(__DIR__.'/Http/routes.php');
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
