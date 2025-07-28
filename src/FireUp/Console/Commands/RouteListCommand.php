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
        // Handle --docs option
        if (in_array('--docs', $args)) {
            $outputFile = $this->app->getBasePath() . '/public/api-docs.json';
            $routes = $this->app->getRouter()->getRoutes();
            $openapi = [
                'openapi' => '3.0.0',
                'info' => [
                    'title' => 'FireUp API',
                    'version' => '1.0.0',
                ],
                'paths' => []
            ];
            foreach ($routes as $route) {
                if (strpos($route['uri'], '/api/') !== 0) continue;
                $method = strtolower($route['method']);
                $path = $route['uri'];
                if (!isset($openapi['paths'][$path])) {
                    $openapi['paths'][$path] = [];
                }
                $openapi['paths'][$path][$method] = [
                    'operationId' => $route['action'],
                    'summary' => 'Auto-generated endpoint',
                    'parameters' => [],
                    'responses' => [
                        '200' => [
                            'description' => 'Successful response',
                            'content' => [
                                'application/json' => [
                                    'schema' => [ 'type' => 'object' ]
                                ]
                            ]
                        ]
                    ]
                ];
            }
            file_put_contents($outputFile, json_encode($openapi, JSON_PRETTY_PRINT));
            $this->info("API docs generated! View at: http://127.0.0.1:8000/api/docs");
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
        echo "Usage: fireup route:list [--docs]\n\n";
        echo "Options:\n";
        echo "  --help           Show this help message\n";
        echo "  --docs           Generate OpenAPI docs and serve at /api/docs\n\n";
        echo "Example:\n";
        echo "  fireup route:list\n";
        echo "  fireup route:list --docs\n";
    }
} 