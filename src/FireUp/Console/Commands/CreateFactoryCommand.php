<?php

namespace FireUp\Console\Commands;

use FireUp\Application;

class CreateFactoryCommand extends Command
{
    public function handle(array $args)
    {
        if (empty($args)) {
            $this->error('Please provide a factory name.');
            $this->showHelp();
            return 1;
        }
        $name = $args[0];
        $className = ucfirst($name) . 'Factory';
        $fileName = $className . '.php';
        $targetDir = $this->app->getBasePath() . '/src/FireUp/Database/Factories/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $targetFile = $targetDir . $fileName;
        if (file_exists($targetFile)) {
            $this->error("Factory already exists: {$fileName}");
            return 1;
        }
        $namespace = 'FireUp\\Database\\Factories';
        $template = <<<PHP
<?php

namespace {$namespace};

class {$className}
{
    public function definition()
    {
        // Your factory definition here
        return [];
    }
}
PHP;
        file_put_contents($targetFile, $template);
        $this->info("Factory created: src/FireUp/Database/Factories/{$fileName}");
        return 0;
    }
    public function getSignature() { return 'make:factory <Name>'; }
    public function getDescription() { return 'Create a new factory class.'; }
    protected function showHelp() {
        echo "Usage: fireup make:factory <Name>\n\n";
        echo "Creates a new factory class in src/FireUp/Database/Factories/.\n";
    }
} 