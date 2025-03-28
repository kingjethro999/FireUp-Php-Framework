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
     * @param  Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->registerCommands();
    }

    /**
     * Register the framework commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        $this->commands = [
            'serve' => ServeCommand::class,
            'create:model' => CreateModelCommand::class,
            'create:controller' => CreateControllerCommand::class,
            'create:view' => CreateViewCommand::class,
            'golive' => GoLiveCommand::class,
            'migrate' => MigrateCommand::class,
            'rollback' => RollbackCommand::class,
            'route:list' => RouteListCommand::class,
        ];
    }

    /**
     * Run the console application.
     *
     * @param  array  $argv
     * @return int
     */
    public function run(array $argv)
    {
        // Remove the script name from arguments
        array_shift($argv);

        // Get the command name
        $command = array_shift($argv);

        if (!$command) {
            $this->showHelp();
            return 0;
        }

        // Check if command exists
        if (!isset($this->commands[$command])) {
            $this->error("Command '{$command}' not found.");
            return 1;
        }

        // Create and run the command
        $commandClass = $this->commands[$command];
        $commandInstance = new $commandClass($this->app);
        return $commandInstance->handle($argv);
    }

    /**
     * Show the help message.
     *
     * @return void
     */
    protected function showHelp()
    {
        echo "FireUp Framework CLI\n\n";
        echo "Available commands:\n";
        echo "  serve              Start the FireUp development server\n";
        echo "  create:model       Create a new model\n";
        echo "  create:controller  Create a new controller\n";
        echo "  create:view        Create a new view\n";
        echo "  golive             Prepare project for hosting\n";
        echo "  migrate            Run database migrations\n";
        echo "  rollback           Rollback the last migration\n";
        echo "  route:list         List all registered routes\n\n";
        echo "For more information about a command, run:\n";
        echo "  fireup <command> --help\n";
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