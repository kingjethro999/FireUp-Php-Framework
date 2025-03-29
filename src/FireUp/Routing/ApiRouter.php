<?php

namespace FireUp\Routing;

use FireUp\Application;

class ApiRouter
{
    protected $app;
    protected $routes = [];

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function registerApiRoutes($model, $options = [])
    {
        $baseUrl = '/api/' . ($options['version'] ?? 'v1') . '/' . strtolower($model);
        
        // GET /api/v1/{resource}
        $this->app->getRouter()->get($baseUrl, "App\\Controllers\\Api\\{$model}Controller@index");
        
        // GET /api/v1/{resource}/{id}
        $this->app->getRouter()->get($baseUrl . '/{id}', "App\\Controllers\\Api\\{$model}Controller@show");
        
        // POST /api/v1/{resource}
        $this->app->getRouter()->post($baseUrl, "App\\Controllers\\Api\\{$model}Controller@store");
        
        // PUT /api/v1/{resource}/{id}
        $this->app->getRouter()->put($baseUrl . '/{id}', "App\\Controllers\\Api\\{$model}Controller@update");
        
        // DELETE /api/v1/{resource}/{id}
        $this->app->getRouter()->delete($baseUrl . '/{id}', "App\\Controllers\\Api\\{$model}Controller@destroy");

        // Register additional endpoints based on options
        if (isset($options['endpoints'])) {
            foreach ($options['endpoints'] as $endpoint) {
                $this->registerCustomEndpoint($baseUrl, $model, $endpoint);
            }
        }
    }

    protected function registerCustomEndpoint($baseUrl, $model, $endpoint)
    {
        $controller = "App\\Controllers\\Api\\{$model}Controller";
        
        switch ($endpoint) {
            case 'search':
                $this->app->getRouter()->get($baseUrl . '/search', "$controller@search");
                break;
            case 'bulk':
                $this->app->getRouter()->post($baseUrl . '/bulk', "$controller@bulkStore");
                break;
            case 'relationships':
                $this->app->getRouter()->get($baseUrl . '/{id}/relationships/{relation}', "$controller@relationships");
                break;
        }
    }

    public function getRoutes()
    {
        return $this->routes;
    }
} 