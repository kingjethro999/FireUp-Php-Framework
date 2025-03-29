<?php

namespace FireUp\Console\Commands;

use FireUp\Application;

class CreateViewCommand extends Command
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

        if (empty($args)) {
            $this->error('Please specify a view name');
            $this->showHelp();
            return 1;
        }

        $viewName = array_shift($args);
        $viewPath = $this->app->getBasePath() . '/resources/views/' . str_replace('.', '/', $viewName) . '.php';

        if (file_exists($viewPath)) {
            $this->error("View {$viewName} already exists!");
            return 1;
        }

        // Create directory if it doesn't exist
        $directory = dirname($viewPath);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $content = $this->generateViewContent($viewName);
        file_put_contents($viewPath, $content);

        $this->info("View {$viewName} created successfully!");
        return 0;
    }

    /**
     * Generate the view file content.
     *
     * @param  string  $viewName
     * @return string
     */
    protected function generateViewContent($viewName)
    {
        return <<<EOT
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ \$title ?? 'Welcome' }}</h1>
        <p>This is your new view: {{ $viewName }}</p>
    </div>
@endsection
EOT;
    }

    /**
     * Get the command signature.
     *
     * @return string
     */
    public function getSignature()
    {
        return 'create:view <name>';
    }

    /**
     * Get the command description.
     *
     * @return string
     */
    public function getDescription()
    {
        return 'Create a new view file';
    }

    /**
     * Show the command help.
     *
     * @return void
     */
    protected function showHelp()
    {
        echo "Usage: fireup create:view <name>\n\n";
        echo "Arguments:\n";
        echo "  name              The name of the view (can use dot notation for subdirectories)\n\n";
        echo "Options:\n";
        echo "  --help           Show this help message\n\n";
        echo "Example:\n";
        echo "  fireup create:view welcome\n";
        echo "  fireup create:view users.index\n";
    }
} 