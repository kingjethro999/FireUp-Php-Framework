<?php

if (!function_exists('csrf_token')) {
    /**
     * Generate a CSRF token.
     *
     * @return string
     */
    function csrf_token()
    {
        if (!isset($_SESSION['_token'])) {
            $_SESSION['_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_token'];
    }
}

if (!function_exists('asset')) {
    /**
     * Generate an asset URL.
     *
     * @param string $path
     * @return string
     */
    function asset($path)
    {
        return '/' . ltrim($path, '/');
    }
}

if (!function_exists('url')) {
    /**
     * Generate a URL.
     *
     * @param string $path
     * @return string
     */
    function url($path = '')
    {
        $base = $_ENV['APP_URL'] ?? 'http://localhost';
        return rtrim($base, '/') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('redirect')) {
    /**
     * Redirect to a URL.
     *
     * @param string $url
     * @return void
     */
    function redirect($url)
    {
        header('Location: ' . $url);
        exit;
    }
}

if (!function_exists('old')) {
    /**
     * Get old input value.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function old($key, $default = '')
    {
        return $_SESSION['old'][$key] ?? $default;
    }
}

if (!function_exists('config')) {
    /**
     * Get configuration value.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function config($key, $default = null)
    {
        $keys = explode('.', $key);
        $config = require __DIR__ . '/../../config/' . $keys[0] . '.php';
        
        unset($keys[0]);
        foreach ($keys as $segment) {
            if (!isset($config[$segment])) {
                return $default;
            }
            $config = $config[$segment];
        }
        
        return $config;
    }
} 