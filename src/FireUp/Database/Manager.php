<?php

namespace FireUp\Database;

use FireUp\Application;
use PDO;

class Manager
{
    /**
     * The application instance.
     *
     * @var Application
     */
    protected $app;

    /**
     * The database connections.
     *
     * @var array
     */
    protected $connections = [];

    /**
     * Create a new database manager instance.
     *
     * @param  Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Get a database connection.
     *
     * @param  string  $name
     * @return Connection
     */
    public function connection($name = null)
    {
        $name = $name ?: $this->getDefaultConnection();

        if (!isset($this->connections[$name])) {
            $this->connections[$name] = $this->makeConnection($name);
        }

        return $this->connections[$name];
    }

    /**
     * Make a database connection.
     *
     * @param  string  $name
     * @return Connection
     */
    protected function makeConnection($name)
    {
        $config = $this->getConnectionConfig($name);

        $dsn = sprintf(
            '%s:host=%s;port=%s;dbname=%s;charset=%s',
            $config['driver'],
            $config['host'],
            $config['port'],
            $config['database'],
            $config['charset']
        );

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        $pdo = new PDO($dsn, $config['username'], $config['password'], $options);

        return new Connection($pdo);
    }

    /**
     * Get the default connection name.
     *
     * @return string
     */
    protected function getDefaultConnection()
    {
        return $this->app->make('config')->get('database.default', 'mysql');
    }

    /**
     * Get the configuration for a connection.
     *
     * @param  string  $name
     * @return array
     */
    protected function getConnectionConfig($name)
    {
        $connections = $this->app->make('config')->get('database.connections', []);
        
        if (!isset($connections[$name])) {
            throw new \Exception("Database connection [{$name}] not configured.");
        }

        return $connections[$name];
    }
} 