<?php

namespace FireUp\Console\Commands;

use FireUp\Application;
use FireUp\Database\Migration\Migration;

class RollbackCommand extends Command
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

        $this->info('Rolling back migrations...');

        $migration = new Migration($this->app);
        $migration->rollback();

        $this->info('Migrations rolled back successfully!');
        return 0;
    }

    /**
     * Get the command signature.
     *
     * @return string
     */
    public function getSignature()
    {
        return 'rollback';
    }

    /**
     * Get the command description.
     *
     * @return string
     */
    public function getDescription()
    {
        return 'Rollback the last database migration';
    }

    /**
     * Show the command help.
     *
     * @return void
     */
    protected function showHelp()
    {
        echo "Usage: fireup rollback\n\n";
        echo "Options:\n";
        echo "  --help           Show this help message\n\n";
        echo "Example:\n";
        echo "  fireup rollback\n";
    }
} 