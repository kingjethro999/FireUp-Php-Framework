<?php
use Dotenv\Dotenv;

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Load Environment Variables
|--------------------------------------------------------------------------
|
| We will load the environment variables from the .env file into the
| $_ENV super-global variable. This allows us to access them throughout
| the application.
|
*/

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/..');
$dotenv->load();

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new FireUp application instance
| which serves as the "glue" for all the components of FireUp, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new FireUp\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

// Add API docs serving
if ($_SERVER['REQUEST_URI'] === '/api/docs') {
    header('Content-Type: text/html');
    echo '<!DOCTYPE html><html><head><title>FireUp API Docs</title></head><body>';
    echo '<h1>FireUp API Documentation</h1>';
    echo '<div id="swagger-ui"></div>';
    echo '<script src="https://unpkg.com/swagger-ui-dist@4/swagger-ui-bundle.js"></script>';
    echo '<script>const ui = SwaggerUIBundle({url: "/api-docs.json", dom_id: "#swagger-ui"});</script>';
    echo '</body></html>';
    exit;
}
if ($_SERVER['REQUEST_URI'] === '/api-docs.json') {
    $jsonFile = __DIR__ . '/api-docs.json';
    if (file_exists($jsonFile)) {
        header('Content-Type: application/json');
        readfile($jsonFile);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'API docs not found. Run fireup route:list --docs to generate.']);
    }
    exit;
}

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

$app->run(); 