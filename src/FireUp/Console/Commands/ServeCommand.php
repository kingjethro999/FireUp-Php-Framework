<?php

namespace FireUp\Console\Commands;

use FireUp\Application;
use FireUp\Http\Server\Server;

class ServeCommand extends Command
{
    /**
     * The default host.
     *
     * @var string
     */
    protected $host = 'localhost';

    /**
     * The default port.
     *
     * @var int
     */
    protected $port = 8000;

    /**
     * Handle the command.
     *
     * @param  array  $args
     * @return int
     */
    public function handle(array $args)
    {
        // Parse command arguments
        foreach ($args as $arg) {
            if (strpos($arg, '--host=') === 0) {
                $this->host = substr($arg, 7);
            } elseif (strpos($arg, '--port=') === 0) {
                $this->port = (int) substr($arg, 7);
            } elseif ($arg === '--help') {
                $this->showHelp();
                return 0;
            }
        }

        $this->info("Starting FireUp development server...");
        $this->info("Server running at http://{$this->host}:{$this->port}");
        $this->info("Press Ctrl+C to stop the server.");

        // Create and start the server
        $server = new Server($this->app, $this->host, $this->port);
        return $server->start();
    }

    /**
     * Get the command signature.
     *
     * @return string
     */
    public function getSignature()
    {
        return 'serve';
    }

    /**
     * Get the command description.
     *
     * @return string
     */
    public function getDescription()
    {
        return 'Start the FireUp development server with web-based setup interface';
    }

    /**
     * Show the command help.
     *
     * @return void
     */
    protected function showHelp()
    {
        echo "Usage: fireup serve [options]\n\n";
        echo "Options:\n";
        echo "  --host=<host>    The host to bind the server to (default: localhost)\n";
        echo "  --port=<port>    The port to bind the server to (default: 8000)\n";
        echo "  --help          Show this help message\n\n";
        echo "Example:\n";
        echo "  fireup serve\n";
        echo "  fireup serve --host=0.0.0.0 --port=8080\n";
    }
} 