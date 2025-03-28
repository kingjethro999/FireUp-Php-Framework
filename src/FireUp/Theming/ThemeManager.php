<?php

namespace FireUp\Theming;

use FireUp\Application;
use FireUp\Exceptions\ThemeNotFoundException;

class ThemeManager
{
    /**
     * The application instance.
     *
     * @var Application
     */
    protected $app;

    /**
     * The current active theme.
     *
     * @var Theme
     */
    protected $currentTheme;

    /**
     * The available themes.
     *
     * @var array
     */
    protected $themes = [];

    /**
     * Create a new theme manager instance.
     *
     * @param  Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->loadThemes();
    }

    /**
     * Load all available themes.
     *
     * @return void
     */
    protected function loadThemes()
    {
        $themesPath = $this->app->getBasePath() . '/themes';
        
        if (!is_dir($themesPath)) {
            mkdir($themesPath, 0755, true);
        }

        foreach (glob($themesPath . '/*', GLOB_ONLYDIR) as $themePath) {
            $themeName = basename($themePath);
            $themeClass = "Themes\\{$themeName}\\Theme";
            
            if (class_exists($themeClass)) {
                $this->themes[$themeName] = new $themeClass($this->app);
            }
        }
    }

    /**
     * Get the current active theme.
     *
     * @return Theme
     */
    public function getCurrentTheme()
    {
        return $this->currentTheme;
    }

    /**
     * Set the active theme.
     *
     * @param  string  $themeName
     * @return void
     * @throws ThemeNotFoundException
     */
    public function setTheme($themeName)
    {
        if (!isset($this->themes[$themeName])) {
            throw new ThemeNotFoundException("Theme '{$themeName}' not found.");
        }

        $this->currentTheme = $this->themes[$themeName];
    }

    /**
     * Get all available themes.
     *
     * @return array
     */
    public function getThemes()
    {
        return $this->themes;
    }

    /**
     * Check if a theme exists.
     *
     * @param  string  $themeName
     * @return bool
     */
    public function hasTheme($themeName)
    {
        return isset($this->themes[$themeName]);
    }

    /**
     * Get a theme by name.
     *
     * @param  string  $themeName
     * @return Theme|null
     */
    public function getTheme($themeName)
    {
        return $this->themes[$themeName] ?? null;
    }
} 