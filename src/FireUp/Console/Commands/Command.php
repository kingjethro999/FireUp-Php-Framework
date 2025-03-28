<?php

namespace FireUp\Console\Commands;

use FireUp\Application;

abstract class Command
{
    /**
     * The application instance.
     *
     * @var Application
     */
    protected $app;

    /**
     * Create a new command instance.
     *
     * @param  Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Handle the command.
     *
     * @param  array  $args
     * @return int
     */
    abstract public function handle(array $args);

    /**
     * Write a string as standard output.
     *
     * @param  string  $string
     * @return void
     */
    protected function info($string)
    {
        echo "\033[32m{$string}\033[0m\n";
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

    /**
     * Write a string as warning output.
     *
     * @param  string  $string
     * @return void
     */
    protected function warning($string)
    {
        echo "\033[33m{$string}\033[0m\n";
    }

    /**
     * Write a string as comment output.
     *
     * @param  string  $string
     * @return void
     */
    protected function comment($string)
    {
        echo "\033[36m{$string}\033[0m\n";
    }

    /**
     * Get the command signature.
     *
     * @return string
     */
    abstract public function getSignature();

    /**
     * Get the command description.
     *
     * @return string
     */
    abstract public function getDescription();
} 