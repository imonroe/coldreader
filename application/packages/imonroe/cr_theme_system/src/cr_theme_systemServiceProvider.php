<?php

namespace imonroe\cr_theme_system;

use Illuminate\Support\ServiceProvider;

class cr_theme_systemServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__.'/views', 'cr_theme_system');
        
        //$this->publishes([
        //	__DIR__.'/path/to/views' => resource_path('views/vendor/courier'),
        //]);

        // Routes:
        $this->loadRoutesFrom(__DIR__.'/Http/routes.php');


        $preferences_registry = app()->make('ApplicationPreferencesRegistry');
        $theme_pref = [
                        'preference' => 'cr_theme', 
                        'preference_label' => 'Select your prefered theme', 
                        'field_type' => 'select', 
                        'default_value' => 'default_light',
                        'options' => ['default_dark' => 'Dark', 'default_light' => 'Light'], 
                        ];
        $preferences_registry->register_preference($theme_pref);    

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