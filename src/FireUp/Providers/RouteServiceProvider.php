<?php

namespace FireUp\Providers;

use FireUp\Application;
use FireUp\Routing\Router;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Register the router service
        $this->singleton('router', function ($app) {
            return new Router($app);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Load the routes
        $this->loadRoutes();
    }

    /**
     * Load the application routes.
     *
     * @return void
     */
    protected function loadRoutes()
    {
        $routesPath = $this->app->getBasePath() . '/routes';

        if (!is_dir($routesPath)) {
            return;
        }

        // Load web routes
        $webRoutes = $routesPath . '/web.php';
        if (file_exists($webRoutes)) {
            require $webRoutes;
        }

        // Load API routes
        $apiRoutes = $routesPath . '/api.php';
        if (file_exists($apiRoutes)) {
            require $apiRoutes;
        }
    }
} 