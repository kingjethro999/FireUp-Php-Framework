<?php

namespace FireUp\Console\Commands;

use FireUp\Application;
use FireUp\Database\Migration\Migration;

class MigrateCommand extends Command
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
            return 0;
        }

        $this->info('Running migrations...');

        $migrationsPath = $this->app->getBasePath() . '/database/migrations';
        if (!file_exists($migrationsPath)) {
            mkdir($migrationsPath, 0755, true);
        }

        $migrations = glob($migrationsPath . '/*.php');
        if (empty($migrations)) {
            $this->info('No migrations found.');
            return 0;
        }

        $migration = new Migration($this->app);
        $migration->createMigrationsTable();

        foreach ($migrations as $file) {
            $migration->run($file);
        }

        $this->info('Migrations completed successfully.');
        return 0;
    }

    /**
     * Get the command signature.
     *
     * @return string
     */
    public function getSignature()
    {
        return 'migrate';
    }

    /**
     * Get the command description.
     *
     * @return string
     */
    public function getDescription()
    {
        return 'Run database migrations';
    }

    /**
     * Show the command help.
     *
     * @return void
     */
    protected function showHelp()
    {
        echo "Usage: fireup migrate [options]\n\n";
        echo "Options:\n";
        echo "  --help  Show this help message\n\n";
        echo "Example:\n";
        echo "  fireup migrate\n";
    }
} 