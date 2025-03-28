<?php

namespace FireUp\WebSocket;

use FireUp\Application;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class WebSocketHandler implements MessageComponentInterface
{
    /**
     * The application instance.
     *
     * @var Application
     */
    protected $app;

    /**
     * The connected clients.
     *
     * @var \SplObjectStorage
     */
    protected \SplObjectStorage $clients;

    /**
     * Create a new WebSocket handler instance.
     *
     * @param  Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->clients = new \SplObjectStorage;
    }

    /**
     * Handle new connections.
     *
     * @param  ConnectionInterface  $conn
     * @return void
     */
    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        $conn->send(json_encode([
            'type' => 'connection',
            'message' => 'Connected successfully'
        ]));
    }

    /**
     * Handle incoming messages.
     *
     * @param  ConnectionInterface  $from
     * @param  string  $msg
     * @return void
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $client->send(json_encode([
                    'type' => 'message',
                    'from' => $from->resourceId,
                    'data' => $data
                ]));
            }
        }
    }

    /**
     * Handle client disconnections.
     *
     * @param  ConnectionInterface  $conn
     * @return void
     */
    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
    }

    /**
     * Handle errors.
     *
     * @param  ConnectionInterface  $conn
     * @param  \Exception  $e
     * @return void
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
    }

    /**
     * Broadcast a message to all connected clients.
     *
     * @param  mixed  $data
     * @return void
     */
    public function broadcast($data)
    {
        foreach ($this->clients as $client) {
            $client->send(json_encode([
                'type' => 'broadcast',
                'data' => $data
            ]));
        }
    }
} 