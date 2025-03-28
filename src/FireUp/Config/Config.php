<?php

namespace FireUp\Config;

class Config
{
    /**
     * The base path of the application.
     *
     * @var string
     */
    protected $basePath;

    /**
     * The configuration cache.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Create a new configuration instance.
     *
     * @param  string  $basePath
     * @return void
     */
    public function __construct($basePath)
    {
        $this->basePath = $basePath;
    }

    /**
     * Load the configuration files.
     *
     * @return void
     */
    public function load()
    {
        $configPath = $this->basePath . '/config';

        if (!is_dir($configPath)) {
            return;
        }

        foreach (glob($configPath . '/*.php') as $file) {
            $key = basename($file, '.php');
            $this->config[$key] = require $file;
        }
    }

    /**
     * Get a configuration value.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $keys = explode('.', $key);
        $value = $this->config;

        foreach ($keys as $segment) {
            if (!isset($value[$segment])) {
                return $default;
            }

            $value = $value[$segment];
        }

        return $value;
    }

    /**
     * Set a configuration value.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function set($key, $value)
    {
        $keys = explode('.', $key);
        $config = &$this->config;

        foreach ($keys as $segment) {
            if (!isset($config[$segment])) {
                $config[$segment] = [];
            }

            $config = &$config[$segment];
        }

        $config = $value;
    }

    /**
     * Check if a configuration value exists.
     *
     * @param  string  $key
     * @return bool
     */
    public function has($key)
    {
        $keys = explode('.', $key);
        $value = $this->config;

        foreach ($keys as $segment) {
            if (!isset($value[$segment])) {
                return false;
            }

            $value = $value[$segment];
        }

        return true;
    }
} 