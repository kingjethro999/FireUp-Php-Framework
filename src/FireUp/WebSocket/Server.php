<?php

namespace FireUp\WebSocket;

use FireUp\Application;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Loop;
use React\Socket\TcpServer;
use React\EventLoop\LoopInterface;

class Server
{
    /**
     * The application instance.
     *
     * @var Application
     */
    protected $app;

    /**
     * The WebSocket server instance.
     *
     * @var IoServer
     */
    protected $server;

    /**
     * The event loop instance.
     *
     * @var LoopInterface
     */
    protected $loop;

    /**
     * Create a new WebSocket server instance.
     *
     * @param  Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Start the WebSocket server.
     *
     * @param  int  $port
     * @return void
     */
    public function start($port = 8080)
    {
        $this->loop = Loop::get();
        $socket = new TcpServer('0.0.0.0:' . $port, $this->loop);
        
        $this->server = new IoServer(
            new HttpServer(
                new WsServer(
                    new WebSocketHandler($this->app)
                )
            ),
            $socket,
            $this->loop
        );

        $this->server->run();
    }

    /**
     * Stop the WebSocket server.
     *
     * @return void
     */
    public function stop()
    {
        if ($this->loop) {
            $this->loop->stop();
        }
    }
} 