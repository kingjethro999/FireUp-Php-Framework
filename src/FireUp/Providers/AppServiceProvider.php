<?php

namespace FireUp\Providers;

use FireUp\Application;
use FireUp\Container;
use FireUp\Config\Config;
use FireUp\Log\Logger;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Register the config service
        $this->singleton('config', function ($app) {
            return new Config($app->getBasePath());
        });

        // Register the logger service
        $this->singleton('log', function ($app) {
            return new Logger($app->getBasePath());
        });

        // Register the router service
        $this->singleton('router', function ($app) {
            return new \FireUp\Routing\Router($app);
        });

        // Register the request service
        $this->singleton('request', function ($app) {
            return \FireUp\Http\Request::createFromGlobals();
        });

        // Register the response service
        $this->singleton('response', function ($app) {
            return new \FireUp\Http\Response();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Load configuration
        $this->app->make('config')->load();

        // Initialize logging
        $this->app->make('log')->initialize();
    }
} 