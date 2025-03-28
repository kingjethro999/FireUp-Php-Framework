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
        if (empty($args[0])) {
            $this->error('Please provide a view name.');
            $this->showHelp();
            return 1;
        }

        $viewName = $args[0];
        $layout = $this->getLayout($args);
        $force = in_array('--force', $args);

        // Create the view file
        $viewPath = $this->app->getBasePath() . '/resources/views/' . str_replace('.', '/', $viewName) . '.php';
        $viewDir = dirname($viewPath);

        if (!file_exists($viewDir)) {
            mkdir($viewDir, 0755, true);
        }

        if (file_exists($viewPath) && !$force) {
            $this->error("View {$viewName} already exists. Use --force to overwrite.");
            return 1;
        }

        $content = $this->generateViewContent($viewName, $layout);
        file_put_contents($viewPath, $content);
        $this->info("View {$viewName} created successfully!");

        return 0;
    }

    /**
     * Get the layout from command arguments.
     *
     * @param  array  $args
     * @return string
     */
    protected function getLayout(array $args)
    {
        foreach ($args as $arg) {
            if (strpos($arg, '--layout=') === 0) {
                return substr($arg, 9);
            }
        }

        return 'layouts.app';
    }

    /**
     * Generate the view file content.
     *
     * @param  string  $viewName
     * @param  string  $layout
     * @return string
     */
    protected function generateViewContent($viewName, $layout)
    {
        return <<<EOT
@extends('{$layout}')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('{$viewName}') }}</div>

                <div class="card-body">
                    <!-- Add your content here -->
                </div>
            </div>
        </div>
    </div>
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
        return 'create:view';
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
        echo "Usage: fireup create:view <name> [options]\n\n";
        echo "Options:\n";
        echo "  --layout=<layout>  The layout to extend (default: layouts.app)\n";
        echo "  --force           Overwrite the view if it exists\n";
        echo "  --help           Show this help message\n\n";
        echo "Example:\n";
        echo "  fireup create:view welcome\n";
        echo "  fireup create:view auth.login --layout=auth\n";
    }
} 