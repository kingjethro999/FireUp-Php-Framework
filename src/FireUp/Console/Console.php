<?php

namespace FireUp\Console;

use FireUp\Application;
use FireUp\Console\Commands\ServeCommand;
use FireUp\Console\Commands\CreateModelCommand;
use FireUp\Console\Commands\CreateControllerCommand;
use FireUp\Console\Commands\CreateViewCommand;
use FireUp\Console\Commands\GoLiveCommand;
use FireUp\Console\Commands\MigrateCommand;
use FireUp\Console\Commands\RollbackCommand;
use FireUp\Console\Commands\RouteListCommand;

class Console
{
    /**
     * The application instance.
     *
     * @var Application
     */
    protected $app;

    /**
     * The registered commands.
     *
     * @var array
     */
    protected $commands = [];

    /**
     * Create a new console instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->app = new \FireUp\Application();
        $this->registerCommands();
    }

    /**
     * Register the framework commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        // Register built-in commands
        $this->commands = [
            'serve' => new Commands\ServeCommand($this->app),
            'create:model' => new Commands\CreateModelCommand($this->app),
            'create:controller' => new Commands\CreateControllerCommand($this->app),
            'create:view' => new Commands\CreateViewCommand($this->app),
            'golive' => new Commands\GoLiveCommand($this->app),
            'migrate' => new Commands\MigrateCommand($this->app),
            'rollback' => new Commands\RollbackCommand($this->app),
            'route:list' => new Commands\RouteListCommand($this->app),
        ];
    }

    /**
     * Run the console application.
     *
     * @return void
     */
    public function run()
    {
        $args = $_SERVER['argv'];
        array_shift($args); // Remove the script name

        if (empty($args)) {
            $this->showHelp();
            return;
        }

        $command = array_shift($args);

        if ($command === '--help' || $command === '-h') {
            $this->showHelp();
            return;
        }

        if (!isset($this->commands[$command])) {
            echo "Command not found: {$command}\n";
            $this->showHelp();
            return;
        }

        $this->commands[$command]->handle($args);
    }

    /**
     * Show the help message.
     *
     * @return void
     */
    protected function showHelp()
    {
        echo "FireUp Console\n\n";
        echo "Available commands:\n";
        foreach ($this->commands as $name => $command) {
            echo "  {$name}\t{$command->getDescription()}\n";
        }
        echo "\nFor more information about a command, run:\n";
        echo "  fireup {$name} --help\n";
    }

    /**
     * Write a string as error output.
     *
     * @param  string  $string
     * @return void
     */
    protected function error($string)
    {
        echo "\033[31m{$string}\033[0m\n";
    }
} 