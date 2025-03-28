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

        // Copy project files
        $this->copyProjectFiles($goLiveDir);

        // Create .htaccess file
        $this->createHtaccess($goLiveDir);

        // Show hosting recommendations
        $this->showHostingRecommendations();

        $this->info('Project ready for hosting!');
        $this->info("Files have been copied to the 'Go Live' directory.");
        $this->info('You can now upload these files to your hosting provider.');

        return 0;
    }

    /**
     * Copy project files to the Go Live directory.
     *
     * @param  string  $goLiveDir
     * @return void
     */
    protected function copyProjectFiles($goLiveDir)
    {
        $basePath = $this->app->getBasePath();
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($basePath)
        );

        foreach ($iterator as $file) {
            // Skip directories and files that should be excluded
            if ($this->shouldExclude($file)) {
                continue;
            }

            // Get relative path
            $relativePath = substr($file->getPathname(), strlen($basePath) + 1);
            $targetPath = $goLiveDir . '/' . $relativePath;

            // Create directory if it doesn't exist
            if ($file->isDir()) {
                if (!file_exists($targetPath)) {
                    mkdir($targetPath, 0755, true);
                }
            } else {
                // Copy file
                copy($file->getPathname(), $targetPath);
            }
        }
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
EOT;

        file_put_contents($goLiveDir . '/.htaccess', $htaccess);
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