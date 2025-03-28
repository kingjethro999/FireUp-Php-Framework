<?php

namespace FireUp\Theming;

use FireUp\Application;

interface Theme
{
    /**
     * Create a new theme instance.
     *
     * @param  Application  $app
     * @return void
     */
    public function __construct(Application $app);

    /**
     * Get the theme name.
     *
     * @return string
     */
    public function getName();

    /**
     * Get the theme description.
     *
     * @return string
     */
    public function getDescription();

    /**
     * Get the theme version.
     *
     * @return string
     */
    public function getVersion();

    /**
     * Get the theme author.
     *
     * @return string
     */
    public function getAuthor();

    /**
     * Get the theme's parent theme name.
     *
     * @return string|null
     */
    public function getParent();

    /**
     * Get the theme's assets path.
     *
     * @return string
     */
    public function getAssetsPath();

    /**
     * Get the theme's views path.
     *
     * @return string
     */
    public function getViewsPath();

    /**
     * Render a view with the theme.
     *
     * @param  string  $view
     * @param  array  $data
     * @return string
     */
    public function render($view, array $data = []);
} 