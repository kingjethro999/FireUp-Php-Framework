<?php

namespace FireUp\Console\Commands;

use FireUp\Application;

class RouteListCommand extends Command
{
    /**
     * Handle the command.
     *
     * @param  array  $args
     * @return int
     */
    public function handle(array $args)
    {
        if (in_array('--help', $args)) {
            $this->showHelp();
            return 0;
        }

        $this->info('Registered Routes:');
        $this->info('==================');

        $routes = $this->app->getRouter()->getRoutes();
        
        if (empty($routes)) {
            $this->comment('No routes registered.');
            return 0;
        }

        foreach ($routes as $route) {
            $this->info(sprintf(
                '%s %s -> %s',
                str_pad($route['method'], 7),
                str_pad($route['uri'], 30),
                $route['action']
            ));
        }

        return 0;
    }

    /**
     * Get the command signature.
     *
     * @return string
     */
    public function getSignature()
    {
        return 'route:list';
    }

    /**
     * Get the command description.
     *
     * @return string
     */
    public function getDescription()
    {
        return 'List all registered routes';
    }

    /**
     * Show the command help.
     *
     * @return void
     */
    protected function showHelp()
    {
        echo "Usage: fireup route:list\n\n";
        echo "Options:\n";
        echo "  --help           Show this help message\n\n";
        echo "Example:\n";
        echo "  fireup route:list\n";
    }
} 