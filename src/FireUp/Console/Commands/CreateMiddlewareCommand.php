<?php

namespace FireUp\Console\Commands;

use FireUp\Application;

class CreateMiddlewareCommand extends Command
{
    public function handle(array $args)
    {
        if (empty($args)) {
            $this->error('Please provide a middleware name.');
            $this->showHelp();
            return 1;
        }
        $name = $args[0];
        $className = ucfirst($name) . 'Middleware';
        $fileName = $className . '.php';
        $targetDir = $this->app->getBasePath() . '/src/FireUp/Http/Middleware/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $targetFile = $targetDir . $fileName;
        if (file_exists($targetFile)) {
            $this->error("Middleware already exists: {$fileName}");
            return 1;
        }
        $namespace = 'FireUp\\Http\\Middleware';
        $template = <<<PHP
<?php

namespace {$namespace};

use FireUp\Http\Request;
use FireUp\Http\Response;

class {$className}
{
    public function handle(Request $request, callable $next)
    {
        // Your middleware logic here
        return $next($request);
    }
}
PHP;
        file_put_contents($targetFile, $template);
        $this->info("Middleware created: src/FireUp/Http/Middleware/{$fileName}");
        return 0;
    }
    public function getSignature() { return 'make:middleware <Name>'; }
    public function getDescription() { return 'Create a new middleware class.'; }
    protected function showHelp() {
        echo "Usage: fireup make:middleware <Name>\n\n";
        echo "Creates a new middleware class in src/FireUp/Http/Middleware/.\n";
    }
} 