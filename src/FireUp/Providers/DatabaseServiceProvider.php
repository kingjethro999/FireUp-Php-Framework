<?php

namespace FireUp\Providers;

use FireUp\Application;
use FireUp\Database\Connection;
use FireUp\Database\Manager;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Register the database manager
        $this->singleton('db', function ($app) {
            return new Manager($app);
        });

        // Register the default database connection
        $this->singleton('db.connection', function ($app) {
            return $app->make('db')->connection();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Initialize the database connection
        $this->app->make('db.connection');
    }
} 