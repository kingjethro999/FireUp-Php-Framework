<?php

namespace FireUp\Console\Commands;

use FireUp\Application;

class CreateEventCommand extends Command
{
    public function handle(array $args)
    {
        if (empty($args)) {
            $this->error('Please provide an event name.');
            $this->showHelp();
            return 1;
        }
        $name = $args[0];
        $className = ucfirst($name) . 'Event';
        $fileName = $className . '.php';
        $targetDir = $this->app->getBasePath() . '/src/FireUp/Events/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $targetFile = $targetDir . $fileName;
        if (file_exists($targetFile)) {
            $this->error("Event already exists: {$fileName}");
            return 1;
        }
        $namespace = 'FireUp\\Events';
        $template = <<<PHP
<?php

namespace {$namespace};

class {$className}
{
    // Define your event properties and methods here
}
PHP;
        file_put_contents($targetFile, $template);
        $this->info("Event created: src/FireUp/Events/{$fileName}");
        return 0;
    }
    public function getSignature() { return 'make:event <Name>'; }
    public function getDescription() { return 'Create a new event class.'; }
    protected function showHelp() {
        echo "Usage: fireup make:event <Name>\n\n";
        echo "Creates a new event class in src/FireUp/Events/.\n";
    }
} 