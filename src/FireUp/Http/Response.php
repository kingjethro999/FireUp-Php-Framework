<?php

namespace FireUp\Http;

class Response
{
    /**
     * The response content.
     *
     * @var mixed
     */
    protected $content;

    /**
     * The response status code.
     *
     * @var int
     */
    protected $statusCode;

    /**
     * The response headers.
     *
     * @var array
     */
    protected $headers = [];

    /**
     * Create a new response instance.
     *
     * @param  mixed  $content
     * @param  int  $statusCode
     * @param  array  $headers
     * @return void
     */
    public function __construct($content = '', $statusCode = 200, array $headers = [])
    {
        $this->content = $content;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
    }

    /**
     * Get the response content.
     *
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the response content.
     *
     * @param  mixed  $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Get the response status code.
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Set the response status code.
     *
     * @param  int  $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * Get the response headers.
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Set a header on the response.
     *
     * @param  string  $name
     * @param  string  $value
     * @return $this
     */
    public function setHeader($name, $value)
    {
        $this->headers[$name] = $value;
        return $this;
    }

    /**
     * Send the response back to the client.
     *
     * @return void
     */
    public function send()
    {
        // Set the status code
        http_response_code($this->statusCode);

        // Set the headers
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }

        // Send the content
        echo $this->content;
    }
} 