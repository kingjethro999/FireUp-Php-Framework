<?php

namespace FireUp\Routing;

use FireUp\Application;
use FireUp\Http\Request;
use FireUp\Http\Response;

class Router
{
    /**
     * The application instance.
     *
     * @var Application
     */
    protected $app;

    /**
     * The registered routes.
     *
     * @var array
     */
    protected $routes = [];

    /**
     * Create a new router instance.
     *
     * @param  Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Register a new GET route with the router.
     *
     * @param  string  $uri
     * @param  array|string|callable  $action
     * @return void
     */
    public function get($uri, $action)
    {
        $this->addRoute('GET', $uri, $action);
    }

    /**
     * Register a new POST route with the router.
     *
     * @param  string  $uri
     * @param  array|string|callable  $action
     * @return void
     */
    public function post($uri, $action)
    {
        $this->addRoute('POST', $uri, $action);
    }

    /**
     * Register a new PUT route with the router.
     *
     * @param  string  $uri
     * @param  array|string|callable  $action
     * @return void
     */
    public function put($uri, $action)
    {
        $this->addRoute('PUT', $uri, $action);
    }

    /**
     * Register a new DELETE route with the router.
     *
     * @param  string  $uri
     * @param  array|string|callable  $action
     * @return void
     */
    public function delete($uri, $action)
    {
        $this->addRoute('DELETE', $uri, $action);
    }

    /**
     * Add a route to the underlying route collection.
     *
     * @param  string  $method
     * @param  string  $uri
     * @param  array|string|callable  $action
     * @return void
     */
    protected function addRoute($method, $uri, $action)
    {
        $this->routes[$method][$uri] = $action;
    }

    /**
     * Dispatch the request to the application.
     *
     * @return Response
     */
    public function dispatch()
    {
        $request = $this->app->make('request');
        $method = $request->getMethod();
        $uri = $request->getPathInfo();

        if (!isset($this->routes[$method][$uri])) {
            return new Response('Not Found', 404);
        }

        $action = $this->routes[$method][$uri];

        if (is_callable($action)) {
            return new Response($action($request));
        }

        if (is_array($action)) {
            [$controller, $method] = $action;
            $controller = new $controller($this->app);
            return new Response($controller->$method($request));
        }

        return new Response($action);
    }

    /**
     * Get all registered routes.
     *
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }
} 