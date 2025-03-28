<?php

namespace FireUp\Http\Controllers;

use FireUp\Application;

abstract class ApiController extends Controller
{
    /**
     * Create a new API controller instance.
     *
     * @param  Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    /**
     * Send a success response.
     *
     * @param  mixed  $data
     * @param  string  $message
     * @param  int  $code
     * @return \FireUp\Http\Response
     */
    protected function success($data = null, $message = 'Success', $code = 200)
    {
        return $this->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Send an error response.
     *
     * @param  string  $message
     * @param  int  $code
     * @param  mixed  $errors
     * @return \FireUp\Http\Response
     */
    protected function error($message = 'Error', $code = 400, $errors = null)
    {
        return $this->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors
        ], $code);
    }

    /**
     * Send a JSON response.
     *
     * @param  mixed  $data
     * @param  int  $code
     * @return \FireUp\Http\Response
     */
    protected function json($data, $code = 200)
    {
        return $this->app->make('response')->json($data, $code);
    }
} 