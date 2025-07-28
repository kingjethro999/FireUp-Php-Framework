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
use FireUp\Console\Commands\ApiMobileReadyCommand;

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
            'api:mobile-ready' => new Commands\ApiMobileReadyCommand($this->app),
            'frontend' => new Commands\FrontendCommand($this->app),
            'make:command' => new Commands\CreateCommandCommand($this->app),
            'make:middleware' => new Commands\CreateMiddlewareCommand($this->app),
            'make:policy' => new Commands\CreatePolicyCommand($this->app),
            'make:seeder' => new Commands\CreateSeederCommand($this->app),
            'make:factory' => new Commands\CreateFactoryCommand($this->app),
            'make:event' => new Commands\CreateEventCommand($this->app),
        ];
        // Add 'list' and 'help' as aliases
        $this->commands['list'] = null; // Placeholder, handled in run()
        $this->commands['help'] = null; // Placeholder, handled in run()
    }

    /**
     * Run the console application.
     *
     * @return int
     */
    public function run(array $argv)
    {
        array_shift($argv); // Remove the script name

        if (empty($argv)) {
            $this->showHelp();
            return 0;
        }

        $command = array_shift($argv);

        if ($command === '--help' || $command === '-h') {
            $this->showHelp();
            return 0;
        }

        // Handle 'list' command
        if ($command === 'list') {
            $this->showHelp();
            return 0;
        }

        // Handle 'help <command>'
        if ($command === 'help') {
            $helpCommand = array_shift($argv);
            if ($helpCommand && isset($this->commands[$helpCommand]) && $this->commands[$helpCommand]) {
                $this->commands[$helpCommand]->showHelp();
            } else {
                echo "Unknown command: {$helpCommand}\n";
                $this->showHelp();
            }
            return 0;
        }

        if (!isset($this->commands[$command]) || !$this->commands[$command]) {
            echo "Command not found: {$command}\n";
            $this->showHelp();
            return 1;
        }

        return $this->commands[$command]->handle($argv);
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
            if ($command) {
                echo "  {$name}\t{$command->getDescription()}\n";
            }
        }
        echo "\nUse 'fireup list' to see all commands.";
        echo "\nUse 'fireup help <command>' for more info on a command.\n";
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