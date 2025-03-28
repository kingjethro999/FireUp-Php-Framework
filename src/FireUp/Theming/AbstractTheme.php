<?php

namespace FireUp\Theming;

use FireUp\Application;

abstract class AbstractTheme implements Theme
{
    /**
     * The application instance.
     *
     * @var Application
     */
    protected $app;

    /**
     * The theme's parent theme.
     *
     * @var Theme|null
     */
    protected $parent;

    /**
     * Create a new theme instance.
     *
     * @param  Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->loadParentTheme();
    }

    /**
     * Load the parent theme if one exists.
     *
     * @return void
     */
    protected function loadParentTheme()
    {
        $parentName = $this->getParent();
        if ($parentName) {
            $themeManager = $this->app->getThemeManager();
            if ($themeManager->hasTheme($parentName)) {
                $this->parent = $themeManager->getTheme($parentName);
            }
        }
    }

    /**
     * Get the theme's assets path.
     *
     * @return string
     */
    public function getAssetsPath()
    {
        return $this->app->getBasePath() . '/themes/' . $this->getName() . '/assets';
    }

    /**
     * Get the theme's views path.
     *
     * @return string
     */
    public function getViewsPath()
    {
        return $this->app->getBasePath() . '/themes/' . $this->getName() . '/views';
    }

    /**
     * Render a view with the theme.
     *
     * @param  string  $view
     * @param  array  $data
     * @return string
     */
    public function render($view, array $data = [])
    {
        $viewPath = $this->getViewsPath() . '/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            if ($this->parent) {
                return $this->parent->render($view, $data);
            }
            throw new \RuntimeException("View '{$view}' not found in theme '{$this->getName()}'.");
        }

        extract($data);
        
        ob_start();
        include $viewPath;
        $content = ob_get_clean();

        // Check if layout is specified in the view
        if (isset($layout)) {
            $layoutData = array_merge($data, ['content' => $content]);
            return $this->render($layout, $layoutData);
        }

        return $content;
    }

    /**
     * Get the theme's parent theme.
     *
     * @return Theme|null
     */
    public function getParentTheme()
    {
        return $this->parent;
    }
} 