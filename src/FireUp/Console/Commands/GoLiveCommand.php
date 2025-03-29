<?php

namespace FireUp\Console\Commands;

use FireUp\Application;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class GoLiveCommand extends Command
{
    /**
     * The directories to exclude from the build.
     *
     * @var array
     */
    protected $excludeDirs = [
        'node_modules',
        '.git',
        '.idea',
        '.vscode',
        'vendor',
        'tests',
        'storage/logs',
        'storage/framework/cache',
        'storage/framework/sessions',
        'storage/framework/views',
    ];

    /**
     * The files to exclude from the build.
     *
     * @var array
     */
    protected $excludeFiles = [
        '.env',
        '.env.example',
        '.gitignore',
        'composer.json',
        'composer.lock',
        'package.json',
        'package-lock.json',
        'phpunit.xml',
        'README.md',
        'webpack.mix.js',
    ];

    /**
     * Handle the command.
     *
     * @param  array  $args
     * @return int
     */
    public function handle(array $args)
    {
        if (in_array('--help', $args)) {
            $this->showHelp();
            return 0;
        }

        $this->info('Preparing project for hosting...');

        // Create Go Live directory
        $goLiveDir = $this->app->getBasePath() . '/Go Live';
        if (!file_exists($goLiveDir)) {
            mkdir($goLiveDir, 0755, true);
        }

        // Copy and convert project files
        $this->copyAndConvertFiles($goLiveDir);

        // Create .htaccess file
        $this->createHtaccess($goLiveDir);

        // Create index.php
        $this->createIndexFile($goLiveDir);

        // Show hosting recommendations
        $this->showHostingRecommendations();

        $this->info('Project ready for hosting!');
        $this->info("Files have been copied and converted in the 'Go Live' directory.");
        $this->info('You can now upload these files to your hosting provider.');

        return 0;
    }

    /**
     * Copy and convert project files to the Go Live directory.
     *
     * @param  string  $goLiveDir
     * @return void
     */
    protected function copyAndConvertFiles($goLiveDir)
    {
        $basePath = $this->app->getBasePath();
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($basePath)
        );

        foreach ($iterator as $file) {
            if ($this->shouldExclude($file)) {
                continue;
            }

            $relativePath = substr($file->getPathname(), strlen($basePath) + 1);
            $targetPath = $goLiveDir . '/' . $relativePath;

            if ($file->isDir()) {
                if (!file_exists($targetPath)) {
                    mkdir($targetPath, 0755, true);
                }
            } else {
                if ($file->getExtension() === 'php') {
                    $this->convertPhpFile($file->getPathname(), $targetPath);
                } else {
                    copy($file->getPathname(), $targetPath);
                }
            }
        }
    }

    /**
     * Convert a PHP file to a raw PHP file.
     *
     * @param  string  $sourcePath
     * @param  string  $targetPath
     * @return void
     */
    protected function convertPhpFile($sourcePath, $targetPath)
    {
        $content = file_get_contents($sourcePath);
        
        // Convert namespace imports to require statements
        $content = preg_replace_callback('/use\s+([^;]+);/', function($matches) {
            $namespace = $matches[1];
            $file = str_replace('\\', '/', $namespace) . '.php';
            return "require_once __DIR__ . '/vendor/$file';";
        }, $content);

        // Convert class references to direct file includes
        $content = preg_replace_callback('/new\s+([^\(]+)\(/', function($matches) {
            $class = $matches[1];
            if (strpos($class, '\\') !== false) {
                $file = str_replace('\\', '/', $class) . '.php';
                return "require_once __DIR__ . '/vendor/$file'; new $class(";
            }
            return "new $class(";
        }, $content);

        file_put_contents($targetPath, $content);
    }

    /**
     * Create the .htaccess file for Apache.
     *
     * @param  string  $goLiveDir
     * @return void
     */
    protected function createHtaccess($goLiveDir)
    {
        $htaccess = <<<'EOT'
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [L]

# Security headers
Header set X-Content-Type-Options "nosniff"
Header set X-XSS-Protection "1; mode=block"
Header set X-Frame-Options "SAMEORIGIN"
Header set Referrer-Policy "strict-origin-when-cross-origin"

# PHP settings
php_value upload_max_filesize 10M
php_value post_max_size 10M
php_value max_execution_time 300
php_value max_input_time 300
EOT;

        file_put_contents($goLiveDir . '/public/.htaccess', $htaccess);
    }

    /**
     * Create the index.php file.
     *
     * @param  string  $goLiveDir
     * @return void
     */
    protected function createIndexFile($goLiveDir)
    {
        $indexContent = <<<'EOT'
<?php
define('FIREUP_START', microtime(true));

// Load configuration
require_once __DIR__ . '/config/app.php';

// Initialize database connection
require_once __DIR__ . '/database/connection.php';

// Load routes
require_once __DIR__ . '/routes/web.php';
require_once __DIR__ . '/routes/api.php';

// Initialize application
$app = new FireUp\Application();
$app->run();
EOT;

        file_put_contents($goLiveDir . '/public/index.php', $indexContent);
    }

    /**
     * Check if a file or directory should be excluded.
     *
     * @param  \SplFileInfo  $file
     * @return bool
     */
    protected function shouldExclude($file)
    {
        $relativePath = substr($file->getPathname(), strlen($this->app->getBasePath()) + 1);
        
        // Check if it's in an excluded directory
        foreach ($this->excludeDirs as $dir) {
            if (strpos($relativePath, $dir . '/') === 0) {
                return true;
            }
        }

        // Check if it's an excluded file
        return in_array($relativePath, $this->excludeFiles);
    }

    /**
     * Show hosting recommendations.
     *
     * @return void
     */
    protected function showHostingRecommendations()
    {
        $this->info("\nHosting Recommendations:");
        $this->comment("1. cPanel Hosting:");
        $this->info("   - Upload files to public_html directory");
        $this->info("   - Ensure PHP version matches your project requirements");
        $this->info("   - Enable mod_rewrite for Apache");
        
        $this->comment("\n2. Cloudflare Pages:");
        $this->info("   - Connect your Git repository");
        $this->info("   - Set build command: composer install --no-dev");
        $this->info("   - Set build output directory: Go Live");
        
        $this->comment("\n3. Netlify:");
        $this->info("   - Connect your Git repository");
        $this->info("   - Set build command: composer install --no-dev");
        $this->info("   - Set publish directory: Go Live");
        
        $this->comment("\n4. DigitalOcean App Platform:");
        $this->info("   - Connect your Git repository");
        $this->info("   - Select PHP environment");
        $this->info("   - Set root directory: Go Live");
    }

    /**
     * Get the command signature.
     *
     * @return string
     */
    public function getSignature()
    {
        return 'golive';
    }

    /**
     * Get the command description.
     *
     * @return string
     */
    public function getDescription()
    {
        return 'Prepare project for hosting by creating a production-ready build';
    }

    /**
     * Show the command help.
     *
     * @return void
     */
    protected function showHelp()
    {
        echo "Usage: fireup golive [options]\n\n";
        echo "Options:\n";
        echo "  --help          Show this help message\n\n";
        echo "This command will:\n";
        echo "  1. Create a 'Go Live' directory\n";
        echo "  2. Copy all necessary files (excluding development files)\n";
        echo "  3. Create an .htaccess file for Apache\n";
        echo "  4. Show hosting recommendations\n\n";
        echo "Example:\n";
        echo "  fireup golive\n";
    }
} 