<?php

namespace FireUp\Http\Server;

use FireUp\Application;

class Server
{
    /**
     * The application instance.
     *
     * @var Application
     */
    protected $app;

    /**
     * The host to bind the server to.
     *
     * @var string
     */
    protected $host;

    /**
     * The port to bind the server to.
     *
     * @var int
     */
    protected $port;

    /**
     * Create a new server instance.
     *
     * @param  Application  $app
     * @param  string  $host
     * @param  int  $port
     * @return void
     */
    public function __construct(Application $app, string $host, int $port)
    {
        $this->app = $app;
        $this->host = $host;
        $this->port = $port;
    }

    /**
     * Start the server.
     *
     * @return int
     */
    public function start()
    {
        $command = sprintf(
            'php -S %s:%d %s',
            $this->host,
            $this->port,
            $this->app->getBasePath() . '/public/index.php'
        );

        passthru($command, $status);
        return $status;
    }
} 