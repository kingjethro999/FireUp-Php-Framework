<?php

namespace FireUp\Database\Migration;

use FireUp\Application;
use PDO;

class Migration
{
    /**
     * The application instance.
     *
     * @var Application
     */
    protected $app;

    /**
     * The database connection.
     *
     * @var PDO
     */
    protected $db;

    /**
     * Create a new migration instance.
     *
     * @param  Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->db = $app->getDatabase();
    }

    /**
     * Create the migrations table.
     *
     * @return void
     */
    public function createMigrationsTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS migrations (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            migration VARCHAR(255) NOT NULL,
            batch INTEGER NOT NULL
        )";

        $this->db->exec($sql);
    }

    /**
     * Run the migration.
     *
     * @param  string  $file
     * @return void
     */
    public function run($file)
    {
        $migration = basename($file, '.php');
        
        // Check if migration has already been run
        $stmt = $this->db->prepare("SELECT * FROM migrations WHERE migration = ?");
        $stmt->execute([$migration]);
        
        if ($stmt->fetch()) {
            return;
        }

        // Get the latest batch number
        $stmt = $this->db->query("SELECT MAX(batch) as batch FROM migrations");
        $batch = $stmt->fetch(PDO::FETCH_ASSOC)['batch'] ?? 0;
        $batch++;

        // Run the migration
        require_once $file;
        $class = 'Migration_' . str_replace('_', '', ucwords($migration, '_'));
        $instance = new $class($this->db);
        $instance->up();

        // Record the migration
        $stmt = $this->db->prepare("INSERT INTO migrations (migration, batch) VALUES (?, ?)");
        $stmt->execute([$migration, $batch]);
    }

    /**
     * Rollback the last migration.
     *
     * @return void
     */
    public function rollback()
    {
        // Get the latest batch number
        $stmt = $this->db->query("SELECT MAX(batch) as batch FROM migrations");
        $batch = $stmt->fetch(PDO::FETCH_ASSOC)['batch'] ?? 0;

        if ($batch === 0) {
            return;
        }

        // Get migrations from the last batch
        $stmt = $this->db->prepare("SELECT migration FROM migrations WHERE batch = ?");
        $stmt->execute([$batch]);
        $migrations = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Rollback each migration
        foreach ($migrations as $migration) {
            $file = $this->app->getBasePath() . '/database/migrations/' . $migration . '.php';
            if (file_exists($file)) {
                require_once $file;
                $class = 'Migration_' . str_replace('_', '', ucwords($migration, '_'));
                $instance = new $class($this->db);
                $instance->down();
            }

            // Remove the migration record
            $stmt = $this->db->prepare("DELETE FROM migrations WHERE migration = ?");
            $stmt->execute([$migration]);
        }
    }
} 