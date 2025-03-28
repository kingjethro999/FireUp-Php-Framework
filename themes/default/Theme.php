<?php

namespace Themes\Default;

use FireUp\Theming\AbstractTheme;

class Theme extends AbstractTheme
{
    /**
     * Get the theme name.
     *
     * @return string
     */
    public function getName()
    {
        return 'default';
    }

    /**
     * Get the theme description.
     *
     * @return string
     */
    public function getDescription()
    {
        return 'The default FireUp theme with a modern and clean design.';
    }

    /**
     * Get the theme version.
     *
     * @return string
     */
    public function getVersion()
    {
        return '1.0.0';
    }

    /**
     * Get the theme author.
     *
     * @return string
     */
    public function getAuthor()
    {
        return 'FireUp Team';
    }

    /**
     * Get the theme's parent theme name.
     *
     * @return string|null
     */
    public function getParent()
    {
        return null;
    }
} 