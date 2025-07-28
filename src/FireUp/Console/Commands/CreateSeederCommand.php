<?php

namespace FireUp\Console\Commands;

use FireUp\Application;

class CreateSeederCommand extends Command
{
    public function handle(array $args)
    {
        if (empty($args)) {
            $this->error('Please provide a seeder name.');
            $this->showHelp();
            return 1;
        }
        $name = $args[0];
        $className = ucfirst($name) . 'Seeder';
        $fileName = $className . '.php';
        $targetDir = $this->app->getBasePath() . '/src/FireUp/Database/Seeders/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $targetFile = $targetDir . $fileName;
        if (file_exists($targetFile)) {
            $this->error("Seeder already exists: {$fileName}");
            return 1;
        }
        $namespace = 'FireUp\\Database\\Seeders';
        $template = <<<PHP
<?php

namespace {$namespace};

class {$className}
{
    public function run()
    {
        // Your seeder logic here
    }
}
PHP;
        file_put_contents($targetFile, $template);
        $this->info("Seeder created: src/FireUp/Database/Seeders/{$fileName}");
        return 0;
    }
    public function getSignature() { return 'make:seeder <Name>'; }
    public function getDescription() { return 'Create a new seeder class.'; }
    protected function showHelp() {
        echo "Usage: fireup make:seeder <Name>\n\n";
        echo "Creates a new seeder class in src/FireUp/Database/Seeders/.\n";
    }
} 