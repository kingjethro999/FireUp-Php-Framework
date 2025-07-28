<?php

namespace FireUp\Console\Commands;

use FireUp\Application;

class CreatePolicyCommand extends Command
{
    public function handle(array $args)
    {
        if (empty($args)) {
            $this->error('Please provide a policy name.');
            $this->showHelp();
            return 1;
        }
        $name = $args[0];
        $className = ucfirst($name) . 'Policy';
        $fileName = $className . '.php';
        $targetDir = $this->app->getBasePath() . '/src/FireUp/Policies/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $targetFile = $targetDir . $fileName;
        if (file_exists($targetFile)) {
            $this->error("Policy already exists: {$fileName}");
            return 1;
        }
        $namespace = 'FireUp\\Policies';
        $template = <<<PHP
<?php

namespace {$namespace};

class {$className}
{
    // Define your policy methods here
}
PHP;
        file_put_contents($targetFile, $template);
        $this->info("Policy created: src/FireUp/Policies/{$fileName}");
        return 0;
    }
    public function getSignature() { return 'make:policy <Name>'; }
    public function getDescription() { return 'Create a new policy class.'; }
    protected function showHelp() {
        echo "Usage: fireup make:policy <Name>\n\n";
        echo "Creates a new policy class in src/FireUp/Policies/.\n";
    }
} 