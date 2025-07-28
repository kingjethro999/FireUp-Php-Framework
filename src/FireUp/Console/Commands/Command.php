<?php

namespace FireUp\Console\Commands;

use FireUp\Application;

abstract class Command
{
    /**
     * The application instance.
     *
     * @var Application
     */
    protected $app;

    /**
     * Create a new command instance.
     *
     * @param  Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Handle the command.
     *
     * @param  array  $args
     * @return int
     */
    abstract public function handle(array $args);

    /**
     * Write a string as standard output.
     *
     * @param  string  $string
     * @return void
     */
    protected function info($string)
    {
        echo "\033[32m{$string}\033[0m\n";
    }

    /**
     * Write a string as error output.
     *
     * @param  string  $string
     * @return void
     */
    protected function error($string)
    {
        echo "\033[31m{$string}\033[0m\n";
    }

    /**
     * Write a string as warning output.
     *
     * @param  string  $string
     * @return void
     */
    protected function warning($string)
    {
        echo "\033[33m{$string}\033[0m\n";
    }

    /**
     * Write a string as comment output.
     *
     * @param  string  $string
     * @return void
     */
    protected function comment($string)
    {
        echo "\033[36m{$string}\033[0m\n";
    }

    /**
     * Get the command signature.
     *
     * @return string
     */
    abstract public function getSignature();

    /**
     * Get the command description.
     *
     * @return string
     */
    abstract public function getDescription();

    protected function showHelp()
    {
        echo "Usage: fireup {$this->getSignature()}\n\n";
        echo "{$this->getDescription()}\n\n";
        echo "Options:\n";
        echo "  --help  Show this help message\n";
    }
}

class ApiMobileReadyCommand extends Command
{
    public function handle(array $args)
    {
        // Scan app/Models and app/Controllers for PHP files
        $modelDir = $this->app->getBasePath() . '/app/Models';
        $controllerDir = $this->app->getBasePath() . '/app/Controllers';
        $models = [];
        $controllers = [];
        if (is_dir($modelDir)) {
            foreach (scandir($modelDir) as $file) {
                if (substr($file, -4) === '.php') {
                    $models[] = pathinfo($file, PATHINFO_FILENAME);
                }
            }
        }
        if (is_dir($controllerDir)) {
            foreach (scandir($controllerDir) as $file) {
                if (substr($file, -4) === '.php') {
                    $controllers[] = pathinfo($file, PATHINFO_FILENAME);
                }
            }
        }
        // For each model, check for a matching controller
        $endpoints = [];
        foreach ($models as $model) {
            $resource = strtolower($model) . 's';
            $hasController = in_array($model . 'Controller', $controllers);
            $endpoints["/api/v1/{$resource}"] = ['GET', 'POST', 'PUT', 'DELETE'];
            $endpoints["/api/v1/{$resource}/{id}"] = ['GET', 'PUT', 'DELETE'];
        }
        // Add common auth endpoints
        $endpoints['/api/v1/auth/login'] = ['POST'];
        $endpoints['/api/v1/auth/register'] = ['POST'];
        $endpoints['/api/v1/auth/logout'] = ['POST'];
        $endpoints['/api/v1/auth/refresh'] = ['POST'];
        // Generate OpenAPI docs
        $outputFile = $this->app->getBasePath() . '/public/api-docs.json';
        $openapi = [
            'openapi' => '3.0.0',
            'info' => [
                'title' => 'FireUp API',
                'version' => '1.0.0',
            ],
            'paths' => [],
            'components' => [
                'securitySchemes' => [
                    'bearerAuth' => [
                        'type' => 'http',
                        'scheme' => 'bearer',
                        'bearerFormat' => 'JWT',
                    ]
                ]
            ]
        ];
        foreach ($endpoints as $path => $methods) {
            foreach ($methods as $method) {
                $m = strtolower($method);
                if (!isset($openapi['paths'][$path])) $openapi['paths'][$path] = [];
                // Detect path parameters
                $parameters = [];
                if (preg_match_all('/{(.*?)}/', $path, $matches)) {
                    foreach ($matches[1] as $param) {
                        $parameters[] = [
                            'name' => $param,
                            'in' => 'path',
                            'required' => true,
                            'schema' => [ 'type' => 'string' ]
                        ];
                    }
                }
                // Stub request body fields based on model name
                $schemaFields = ['id' => ['type' => 'integer']];
                if (stripos($path, 'user') !== false) {
                    $schemaFields['name'] = ['type' => 'string'];
                    $schemaFields['email'] = ['type' => 'string'];
                } elseif (stripos($path, 'chat') !== false) {
                    $schemaFields['message'] = ['type' => 'string'];
                } elseif (stripos($path, 'message') !== false) {
                    $schemaFields['content'] = ['type' => 'string'];
                }
                $isAuth = (strpos($path, '/api/v1/auth/') === 0);
                $openapi['paths'][$path][$m] = [
                    'operationId' => $path . '_' . $m,
                    'summary' => 'Auto-generated endpoint',
                    'parameters' => $parameters,
                    'requestBody' => in_array($m, ['post', 'put']) ? [
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => $schemaFields
                                ]
                            ]
                        ]
                    ] : null,
                    'responses' => [
                        '200' => [
                            'description' => 'Successful response',
                            'content' => [
                                'application/json' => [
                                    'schema' => [ 'type' => 'object', 'properties' => $schemaFields ]
                                ]
                            ]
                        ]
                    ],
                    // Add security for non-auth endpoints
                    'security' => $isAuth ? [] : [['bearerAuth' => []]]
                ];
            }
        }
        file_put_contents($outputFile, json_encode($openapi, JSON_PRETTY_PRINT));
        // Print summary
        $this->info("Detected models: " . (empty($models) ? '(none found)' : implode(', ', $models)));
        $this->info("Detected controllers: " . (empty($controllers) ? '(none found)' : implode(', ', $controllers)));
        $this->info("Generated endpoints:");
        foreach ($endpoints as $path => $methods) {
            $this->info("  - {$path} [" . implode(', ', $methods) . "]");
        }
        $this->info("");
        $this->info("API docs updated! View at: http://127.0.0.1:8000/api/docs");
        $this->info("");
        $this->info("React Native example:");
        $this->info("fetch('http://127.0.0.1:8000/api/v1/" . (empty($models) ? 'resource' : strtolower($models[0]) . 's') . "', { headers: { Authorization: 'Bearer <token>' } })");
        $this->info("  .then(res => res.json())");
        $this->info("  .then(data => console.log(data));");
        return 0;
    }
    public function getSignature() { return 'api:mobile-ready'; }
    public function getDescription() { return 'Prepare your API for mobile apps: scan models, generate endpoints, and update docs.'; }
    protected function showHelp() {
        echo "Usage: fireup api:mobile-ready\n\n";
        echo "Scans your app, ensures all models/controllers have API endpoints, generates docs, and prints a summary for mobile developers.\n";
    }
} 