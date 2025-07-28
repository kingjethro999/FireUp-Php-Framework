<?php

namespace FireUp\Console\Commands;

use FireUp\Application;

class CreateCommandCommand extends Command
{
    public function handle(array $args)
    {
        if (empty($args)) {
            $this->error('Please provide a command name.');
            $this->showHelp();
            return 1;
        }
        $name = $args[0];
        $className = ucfirst($name) . 'Command';
        $fileName = $className . '.php';
        $targetDir = $this->app->getBasePath() . '/src/FireUp/Console/Commands/';
        $targetFile = $targetDir . $fileName;
        if (file_exists($targetFile)) {
            $this->error("Command already exists: {$fileName}");
            return 1;
        }
        $namespace = 'FireUp\\Console\\Commands';
        $template = <<<PHP
<?php

namespace {$namespace};

use FireUp\Application;

class {$className} extends Command
{
    public function handle(array \$args)
    {
        // Your command logic here
        \$this->info('{$className} executed!');
        return 0;
    }
    public function getSignature() { return 'make:command {$name}'; }
    public function getDescription() { return 'Custom command: {$name}'; }
    protected function showHelp() {
        echo "Usage: fireup make:command {$name}\n\n";
        echo "Describe what your command does here.\n";
    }
}
PHP;
        file_put_contents($targetFile, $template);
        $this->info("Custom command created: src/FireUp/Console/Commands/{$fileName}");
        return 0;
    }
    public function getSignature() { return 'make:command <Name>'; }
    public function getDescription() { return 'Create a new custom CLI command.'; }
    protected function showHelp() {
        echo "Usage: fireup make:command <Name>\n\n";
        echo "Creates a new custom CLI command class in src/FireUp/Console/Commands/.\n";
    }
} 