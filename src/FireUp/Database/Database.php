<?php

namespace FireUp\Database;

use PDO;
use PDOException;

class Database
{
    /**
     * The PDO instance.
     *
     * @var PDO
     */
    protected $pdo;

    /**
     * Create a new database instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->connect();
    }

    /**
     * Connect to the database.
     *
     * @return void
     */
    protected function connect()
    {
        try {
            $this->pdo = new PDO(
                'sqlite:' . dirname(__DIR__, 2) . '/database/database.sqlite',
                null,
                null,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    /**
     * Get the PDO instance.
     *
     * @return PDO
     */
    public function getPdo()
    {
        return $this->pdo;
    }

    /**
     * Execute a query.
     *
     * @param  string  $sql
     * @param  array  $params
     * @return \PDOStatement
     */
    public function query($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Execute a statement.
     *
     * @param  string  $sql
     * @return int
     */
    public function exec($sql)
    {
        return $this->pdo->exec($sql);
    }
} 