<?php

namespace App\Controllers;

class WelcomeController
{
    public function index()
    {
        require __DIR__ . '/../../view/welcome.php';
    }
} 