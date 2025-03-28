<?php

namespace FireUp\Dashboard;

use FireUp\Application;
use FireUp\Http\Request;
use FireUp\Http\Response;

class DashboardController
{
    /**
     * The application instance.
     *
     * @var Application
     */
    protected $app;

    /**
     * Create a new dashboard controller instance.
     *
     * @param  Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Show the dashboard home page.
     *
     * @return Response
     */
    public function index()
    {
        return new Response($this->view('dashboard.index'));
    }

    /**
     * Show the database setup page.
     *
     * @return Response
     */
    public function database()
    {
        return new Response($this->view('dashboard.database'));
    }

    /**
     * Show the table builder interface.
     *
     * @return Response
     */
    public function tableBuilder()
    {
        return new Response($this->view('dashboard.table-builder'));
    }

    /**
     * Show the model generator interface.
     *
     * @return Response
     */
    public function modelGenerator()
    {
        return new Response($this->view('dashboard.model-generator'));
    }

    /**
     * Show the API generator interface.
     *
     * @return Response
     */
    public function apiGenerator()
    {
        return new Response($this->view('dashboard.api-generator'));
    }

    /**
     * Show the plugin marketplace.
     *
     * @return Response
     */
    public function marketplace()
    {
        return new Response($this->view('dashboard.marketplace'));
    }

    /**
     * Show the live logs interface.
     *
     * @return Response
     */
    public function logs()
    {
        return new Response($this->view('dashboard.logs'));
    }

    /**
     * Show the API tester interface.
     *
     * @return Response
     */
    public function apiTester()
    {
        return new Response($this->view('dashboard.api-tester'));
    }

    /**
     * Render a view.
     *
     * @param  string  $view
     * @param  array  $data
     * @return string
     */
    protected function view($view, array $data = [])
    {
        $viewPath = $this->app->getBasePath() . '/resources/views/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            throw new \Exception("View [{$view}] not found.");
        }

        extract($data);

        ob_start();
        include $viewPath;
        return ob_get_clean();
    }
} 