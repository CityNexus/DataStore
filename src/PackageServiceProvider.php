<?php

namespace CityNexus\DataStore;

use Illuminate\Support\ServiceProvider;

class PackageServiceProvider extends ServiceProvider
{

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        if (! $this->app->routesAreCached()) {
            require __DIR__ . '/routes.php';
        }

        // Publish datastore configs
        $this->publishes([
            __DIR__.'/config.php' => config_path('datastore.php'),
        ]);

        // Public migrations
        $this->loadMigrationsFrom(__DIR__.'/src/migrations');


        // Include Helpers

        // Include Models

        // Include jobs

        // Inluded Policies

    }


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        $this->publishes([
            __DIR__.'/Public' => public_path('vendor/citynexus'),
        ], 'public');

        $this->mergeConfigFrom(
            __DIR__.'/config.php', 'datastore'
        );

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
        ];
    }
}
