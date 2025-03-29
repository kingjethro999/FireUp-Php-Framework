<?php

namespace FireUp\Console\Commands;

use FireUp\Application;

class ServeCommand extends Command
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
            return;
        }

        $host = $args[0] ?? 'localhost';
        $port = $args[1] ?? 8000;

        $this->info("Starting FireUp development server...");
        $this->info("Server running at http://{$host}:{$port}");
        
        $command = sprintf(
            'php -S %s:%d -t %s',
            $host,
            $port,
            $this->app->getBasePath() . '/public'
        );

        passthru($command);
    }

    /**
     * Get the command signature.
     *
     * @return string
     */
    public function getSignature()
    {
        return 'serve [host] [port]';
    }

    /**
     * Get the command description.
     *
     * @return string
     */
    public function getDescription()
    {
        return 'Start the FireUp development server';
    }

    /**
     * Show the command help.
     *
     * @return void
     */
    protected function showHelp()
    {
        echo "Usage: fireup serve [host] [port]\n\n";
        echo "Options:\n";
        echo "  --help          Show this help message\n\n";
        echo "Example:\n";
        echo "  fireup serve\n";
        echo "  fireup serve 0.0.0.0 8080\n";
    }
} 