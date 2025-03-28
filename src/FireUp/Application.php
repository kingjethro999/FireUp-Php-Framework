<?php

namespace FireUp;

use FireUp\Database\Database;
use FireUp\Routing\Router;

class Application
{
    /**
     * The framework version.
     *
     * @var string
     */
    const VERSION = '1.0.0';

    /**
     * The application instance.
     *
     * @var Application
     */
    private static $instance;

    /**
     * The base path of the application.
     *
     * @var string
     */
    private $basePath;

    /**
     * The application's service container.
     *
     * @var Container
     */
    private $container;

    /**
     * The router instance.
     *
     * @var Router
     */
    private $router;

    /**
     * The database instance.
     *
     * @var Database
     */
    private $database;

    /**
     * The theme manager instance.
     *
     * @var \FireUp\Theming\ThemeManager
     */
    protected $themeManager;

    /**
     * Create a new FireUp application instance.
     *
     * @param  string  $basePath
     * @return void
     */
    public function __construct($basePath = null)
    {
        if ($basePath) {
            $this->setBasePath($basePath);
        }

        $this->container = new Container();
        $this->registerBaseBindings();
        $this->registerCoreServiceProviders();
        $this->router = new Router($this);
        $this->database = new Database();
    }

    /**
     * Get the application instance.
     *
     * @return Application
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new static;
        }

        return self::$instance;
    }

    /**
     * Set the base path for the application.
     *
     * @param  string  $basePath
     * @return $this
     */
    public function setBasePath($basePath)
    {
        $this->basePath = rtrim($basePath, '\/');

        return $this;
    }

    /**
     * Get the base path of the application.
     *
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * Register the basic bindings into the container.
     *
     * @return void
     */
    protected function registerBaseBindings()
    {
        $this->container->instance('app', $this);
        $this->container->instance(Container::class, $this->container);
    }

    /**
     * Register all of the base service providers.
     *
     * @return void
     */
    protected function registerCoreServiceProviders()
    {
        $this->container->register(new Providers\AppServiceProvider($this));
        $this->container->register(new Providers\RouteServiceProvider($this));
        $this->container->register(new Providers\DatabaseServiceProvider($this));
    }

    /**
     * Run the application and send the response back to the client.
     *
     * @return void
     */
    public function run()
    {
        $response = $this->container->make('router')->dispatch();
        $response->send();
    }

    /**
     * Resolve the given type from the container.
     *
     * @param  string  $abstract
     * @return mixed
     */
    public function make($abstract)
    {
        return $this->container->make($abstract);
    }

    /**
     * Register a service provider with the application.
     *
     * @param  \FireUp\Providers\ServiceProvider  $provider
     * @return void
     */
    public function register($provider)
    {
        $provider->register();
        $provider->boot();
    }

    /**
     * Get the router instance.
     *
     * @return Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * Get the database instance.
     *
     * @return Database
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * Get the theme manager instance.
     *
     * @return \FireUp\Theming\ThemeManager
     */
    public function getThemeManager()
    {
        if (!$this->themeManager) {
            $this->themeManager = new \FireUp\Theming\ThemeManager($this);
        }
        return $this->themeManager;
    }
} 