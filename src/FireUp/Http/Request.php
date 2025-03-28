<?php

namespace FireUp\Http;

class Request
{
    /**
     * The request parameters.
     *
     * @var array
     */
    protected $parameters = [];

    /**
     * The request headers.
     *
     * @var array
     */
    protected $headers = [];

    /**
     * The request cookies.
     *
     * @var array
     */
    protected $cookies = [];

    /**
     * The request files.
     *
     * @var array
     */
    protected $files = [];

    /**
     * Create a new request instance from the PHP globals.
     *
     * @return static
     */
    public static function createFromGlobals()
    {
        $request = new static();

        $request->parameters = array_merge($_GET, $_POST);
        $request->headers = getallheaders();
        $request->cookies = $_COOKIE;
        $request->files = $_FILES;

        return $request;
    }

    /**
     * Get the request method.
     *
     * @return string
     */
    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    /**
     * Get the request URI.
     *
     * @return string
     */
    public function getUri()
    {
        return $_SERVER['REQUEST_URI'] ?? '/';
    }

    /**
     * Get the request path info.
     *
     * @return string
     */
    public function getPathInfo()
    {
        $uri = $this->getUri();
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        
        if (strpos($uri, $scriptName) === 0) {
            $pathInfo = substr($uri, strlen($scriptName));
        } else {
            $pathInfo = $uri;
        }

        return $pathInfo ?: '/';
    }

    /**
     * Get a parameter from the request.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->parameters[$key] ?? $default;
    }

    /**
     * Get all parameters from the request.
     *
     * @return array
     */
    public function all()
    {
        return $this->parameters;
    }

    /**
     * Get a header from the request.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function header($key, $default = null)
    {
        return $this->headers[$key] ?? $default;
    }

    /**
     * Get all headers from the request.
     *
     * @return array
     */
    public function headers()
    {
        return $this->headers;
    }

    /**
     * Get a cookie from the request.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function cookie($key, $default = null)
    {
        return $this->cookies[$key] ?? $default;
    }

    /**
     * Get all cookies from the request.
     *
     * @return array
     */
    public function cookies()
    {
        return $this->cookies;
    }

    /**
     * Get a file from the request.
     *
     * @param  string  $key
     * @return mixed
     */
    public function file($key)
    {
        return $this->files[$key] ?? null;
    }

    /**
     * Get all files from the request.
     *
     * @return array
     */
    public function files()
    {
        return $this->files;
    }

    /**
     * Check if the request is AJAX.
     *
     * @return bool
     */
    public function isAjax()
    {
        return isset($this->headers['X-Requested-With']) && 
               strtolower($this->headers['X-Requested-With']) === 'xmlhttprequest';
    }

    /**
     * Check if the request is secure.
     *
     * @return bool
     */
    public function isSecure()
    {
        return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
    }
} 