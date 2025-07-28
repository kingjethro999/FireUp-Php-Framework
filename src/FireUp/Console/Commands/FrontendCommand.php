<?php

namespace FireUp\Console\Commands;

use FireUp\Application;

class FrontendCommand extends Command
{
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

        $action = $args[0] ?? 'help';

        switch ($action) {
            case 'install':
                return $this->install();
            case 'dev':
                return $this->dev();
            case 'build':
                return $this->build();
            case 'watch':
                return $this->watch();
            default:
                $this->showHelp();
                return 0;
        }
    }

    /**
     * Install frontend dependencies.
     *
     * @return int
     */
    protected function install()
    {
        $this->info("Installing frontend dependencies...");
        
        if (!file_exists($this->app->getBasePath() . '/package.json')) {
            $this->error("package.json not found. Run 'fireup frontend:init' first.");
            return 1;
        }

        $command = 'npm install';
        $this->info("Running: {$command}");
        
        passthru($command, $status);
        return $status;
    }

    /**
     * Start development server.
     *
     * @return int
     */
    protected function dev()
    {
        $this->info("Starting frontend development server...");
        $this->info("Vite dev server will run on http://localhost:3000");
        $this->info("Press Ctrl+C to stop");
        
        $command = 'npm run dev';
        passthru($command);
        return 0;
    }

    /**
     * Build for production.
     *
     * @return int
     */
    protected function build()
    {
        $this->info("Building frontend assets for production...");
        
        $command = 'npm run build';
        $this->info("Running: {$command}");
        
        passthru($command, $status);
        
        if ($status === 0) {
            $this->info("✅ Frontend assets built successfully!");
            $this->info("Assets are available in public/build/");
        } else {
            $this->error("❌ Build failed!");
        }
        
        return $status;
    }

    /**
     * Watch for changes.
     *
     * @return int
     */
    protected function watch()
    {
        $this->info("Watching for frontend changes...");
        $this->info("Press Ctrl+C to stop");
        
        $command = 'npm run watch';
        passthru($command);
        return 0;
    }

    /**
     * Initialize frontend setup.
     *
     * @return int
     */
    protected function init()
    {
        $this->info("Initializing frontend setup...");
        
        // Create package.json
        $packageJson = [
            'name' => 'fireup-app',
            'private' => true,
            'version' => '1.0.0',
            'description' => 'FireUp PHP Framework Application',
            'scripts' => [
                'dev' => 'vite',
                'build' => 'vite build',
                'preview' => 'vite preview',
                'watch' => 'vite build --watch'
            ],
            'devDependencies' => [
                'vite' => '^5.0.0',
                'tailwindcss' => '^3.4.0',
                'autoprefixer' => '^10.4.16',
                'postcss' => '^8.4.32'
            ],
            'dependencies' => [
                'axios' => '^1.6.0'
            ]
        ];

        file_put_contents(
            $this->app->getBasePath() . '/package.json',
            json_encode($packageJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );

        // Create Vite config
        $viteConfig = "import { defineConfig } from 'vite';\nimport { resolve } from 'path';\n\nexport default defineConfig({\n  root: './',\n  publicDir: 'public',\n  build: {\n    outDir: 'public/build',\n    emptyOutDir: true,\n    rollupOptions: {\n      input: {\n        app: resolve(__dirname, 'resources/js/app.js'),\n        css: resolve(__dirname, 'resources/css/app.css')\n      },\n      output: {\n        entryFileNames: 'js/[name].js',\n        chunkFileNames: 'js/[name].js',\n        assetFileNames: (assetInfo) => {\n          const info = assetInfo.name.split('.');\n          const ext = info[info.length - 1];\n          if (/\\.(css)$/.test(assetInfo.name)) {\n            return `css/[name].\${ext}`;\n          }\n          return `assets/[name].[ext]`;\n        }\n      }\n    }\n  },\n  server: {\n    port: 3000,\n    proxy: {\n      '/api': {\n        target: 'http://localhost:8000',\n        changeOrigin: true\n      }\n    }\n  }\n});";

        file_put_contents($this->app->getBasePath() . '/vite.config.js', $viteConfig);

        // Create directories
        $dirs = [
            'resources/js',
            'resources/css',
            'public/build'
        ];

        foreach ($dirs as $dir) {
            $path = $this->app->getBasePath() . '/' . $dir;
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }
        }

        $this->info("✅ Frontend setup initialized!");
        $this->info("Run 'fireup frontend:install' to install dependencies");
        
        return 0;
    }

    /**
     * Get the command signature.
     *
     * @return string
     */
    public function getSignature()
    {
        return 'frontend [action]';
    }

    /**
     * Get the command description.
     *
     * @return string
     */
    public function getDescription()
    {
        return 'Manage frontend assets and development';
    }

    /**
     * Show the command help.
     *
     * @return void
     */
    protected function showHelp()
    {
        echo "Usage: fireup frontend [action]\n\n";
        echo "Actions:\n";
        echo "  install    Install frontend dependencies\n";
        echo "  dev        Start development server\n";
        echo "  build      Build for production\n";
        echo "  watch      Watch for changes\n";
        echo "  init       Initialize frontend setup\n\n";
        echo "Options:\n";
        echo "  --help     Show this help message\n\n";
        echo "Examples:\n";
        echo "  fireup frontend init\n";
        echo "  fireup frontend install\n";
        echo "  fireup frontend dev\n";
        echo "  fireup frontend build\n";
    }
} 