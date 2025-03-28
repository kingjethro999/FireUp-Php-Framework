<?php

namespace FireUp\Http\Controllers;

use FireUp\Application;

abstract class Controller
{
    /**
     * The application instance.
     *
     * @var Application
     */
    protected $app;

    /**
     * Create a new controller instance.
     *
     * @param  Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Get the application instance.
     *
     * @return Application
     */
    public function getApplication()
    {
        return $this->app;
    }
} 