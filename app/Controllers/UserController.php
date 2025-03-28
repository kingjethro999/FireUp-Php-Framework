<?php

namespace App\Controllers;

use FireUp\Application;
use FireUp\Http\Request;

class UserController
{
    /**
     * The application instance.
     *
     * @var Application
     */
    protected $app;

    /**
     * Create a new controller instance.
     *
     * @param  Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Display a listing of the users.
     *
     * @param  Request  $request
     * @return string
     */
    public function index(Request $request)
    {
        // Example data - in a real application, this would come from the database
        $users = [
            ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com'],
            ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com'],
            ['id' => 3, 'name' => 'Bob Johnson', 'email' => 'bob@example.com'],
        ];

        return json_encode([
            'status' => 'success',
            'data' => $users
        ]);
    }

    /**
     * Store a newly created user.
     *
     * @param  Request  $request
     * @return string
     */
    public function store(Request $request)
    {
        $data = $request->all();

        // In a real application, you would validate the data and store it in the database
        // For this example, we'll just return the received data
        return json_encode([
            'status' => 'success',
            'message' => 'User created successfully',
            'data' => $data
        ]);
    }

    /**
     * Display the specified user.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return string
     */
    public function show(Request $request, $id)
    {
        // Example data - in a real application, this would come from the database
        $user = [
            'id' => $id,
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ];

        return json_encode([
            'status' => 'success',
            'data' => $user
        ]);
    }
} 