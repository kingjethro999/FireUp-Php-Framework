<?php

namespace FireUp\Database;

use PDO;
use PDOStatement;

class Connection
{
    /**
     * The PDO connection instance.
     *
     * @var PDO
     */
    protected $pdo;

    /**
     * Create a new database connection instance.
     *
     * @param  PDO  $pdo
     * @return void
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Execute a query and return the result.
     *
     * @param  string  $query
     * @param  array  $bindings
     * @return PDOStatement
     */
    public function query($query, array $bindings = [])
    {
        $statement = $this->pdo->prepare($query);
        $statement->execute($bindings);
        return $statement;
    }

    /**
     * Execute a query and return the first row.
     *
     * @param  string  $query
     * @param  array  $bindings
     * @return object|null
     */
    public function queryOne($query, array $bindings = [])
    {
        return $this->query($query, $bindings)->fetch();
    }

    /**
     * Execute a query and return all rows.
     *
     * @param  string  $query
     * @param  array  $bindings
     * @return array
     */
    public function queryAll($query, array $bindings = [])
    {
        return $this->query($query, $bindings)->fetchAll();
    }

    /**
     * Execute a query and return the number of affected rows.
     *
     * @param  string  $query
     * @param  array  $bindings
     * @return int
     */
    public function execute($query, array $bindings = [])
    {
        return $this->query($query, $bindings)->rowCount();
    }

    /**
     * Begin a transaction.
     *
     * @return bool
     */
    public function beginTransaction()
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * Commit a transaction.
     *
     * @return bool
     */
    public function commit()
    {
        return $this->pdo->commit();
    }

    /**
     * Rollback a transaction.
     *
     * @return bool
     */
    public function rollBack()
    {
        return $this->pdo->rollBack();
    }

    /**
     * Get the last inserted ID.
     *
     * @return string
     */
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
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
} 